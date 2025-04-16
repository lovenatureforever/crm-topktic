<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\user;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\Category;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

class CampaignController extends Controller
{

    private function _allCampaigns()
    {
        return Campaign::all();
    }

    private function _validate(Request $request)
    {
        $request->validate([
            "name" => "required",
            "company" => "required",
            "category" => "required",
            "sub_category" => "required",
            "start_date" => "required",
            "end_date" => "required",
            "status" => "required",
            "today_sales" => "required",
            "total_sales" => "required"
        ]);
    }

    private function _instantiate(Request $request)
    {
        return new Campaign([
            "name" => $request->name,
            "company" => $request->company,
            "category" => $request->category,
            "sub_category" => $request->sub_category,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "status" => $request->status,
            "today_sales" => $request->today_sales,
            "total_sales" => $request->total_sales
        ]);
    }

    public function getAllCampaigns()
    {
        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }


    public function createCampaign(Request $request)
    {
        $this->_validate($request);
        $campaign = $this->_instantiate($request);
        $campaign->save();

        return response()->json([
            "status" => "success"
        ]);
    }

    public function importCampaignDetail(Request $request, $id)
    {
        $data = [];
        $request->validate([
            'csv' => 'required|file',
        ]);

        $file = $request->file('csv');
        if ($file) {
            $path = $file->store('uploads');
            $contents = Storage::get($path);

            $lines = explode("\n", $contents);
            $index = 0;
            foreach ($lines as $line) {
                if ($index++ == 0)
                    continue;

                if (empty($line))
                    continue;

                $fields = str_getcsv($line);
                if (count($fields) != 39) {
                    Log::info('Pri_no: ' . $fields[0] . ', ' . count($fields));
                    continue;
                }

                $now = now();
                if (empty($fields[4])) {
                    $fields[4] = $now;
                }

                $data[] = [
                    'progressStatus' => $fields[1],
                    'progressSubStatus' => $fields[2],
                    'campaignAgentRemark' => $fields[3],
                    'currentstatusdate' => $fields[4],
                    'applicanttypename' => $fields[5],
                    'applicantidentity' => $fields[6],
                    'applicantcompany' => $fields[7],
                    'applicantbusinessregistrationnumber' => $fields[8],
                    'applicantname' => $fields[9],
                    'applicantgender' => $fields[10],
                    'racename' => $fields[11],
                    'applicantmobile' => $fields[12],
                    'applicantfax' => $fields[13],
                    'applicantotherphone' => $fields[14],
                    'applicantaddress1' => $fields[15],
                    'applicantaddress2' => $fields[16],
                    'applicantaddress3' => $fields[17],
                    'applicantpostcode' => $fields[18],
                    'applicantcity' => $fields[19],
                    'applicantstate' => $fields[20],
                    'applicantemail' => $fields[21],
                    'installationaddress1' => $fields[22],
                    'installationaddress2' => $fields[23],
                    'installationaddress3' => $fields[24],
                    'installationpostcode' => $fields[25],
                    'installationcity' => $fields[26],
                    'installationstate' => $fields[27],
                    'installationpropertytype' => $fields[28],
                    'installationcontactperson' => $fields[29],
                    'installationcontactnumber' => $fields[30],
                    'billingaddress1' => $fields[31],
                    'billingaddress2' => $fields[32],
                    'billingaddress3' => $fields[33],
                    'billingpostcode' => $fields[34],
                    'billingcity' => $fields[35],
                    'billingstate' => $fields[36],
                    'productgroup' => $fields[37],
                    'productname' => $fields[38],
                    'campaign_id' => $id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        } else {
            return response()->json(['error' => 'File was not uploaded correctly'], 400);
        }

        $chunkSize = 100;
        collect($data)->chunk($chunkSize)->each(function ($chunk) {
            CampaignDetail::insert($chunk->toArray());
        });

        return response()->json(['message' => 'Files uploaded and data inserted successfully']);
    }

    public function getCampaignDetailFilters(Request $request, $id)
    {
        $user = $request->user();
        $where = [
            'campaign_id' => $id
        ];

        if ($user->role == UserRole::TEAM_LEADER)
            $where['assigned_leader'] = $user->id;

        if ($user->role == UserRole::AGENT)
            $where['assigned_user'] = $user->id;

        $counts = CampaignDetail::selectRaw('progressStatus, count(*) as count')
            ->where($where)
            ->groupBy('progressStatus')
            ->get()
            ->toArray();

        $counts2 = CampaignDetail::selectRaw('progressStatus, progressSubStatus, count(*) as count')
            ->where($where)
            ->groupBy('progressStatus', 'progressSubStatus')
            ->get()
            ->toArray();

        return response()->json([
            "counts_status" => $counts,
            "counts_substatus" => $counts2
        ]);
    }

    public function getCampaignDetailList(Request $request, $id)
    {
        $user = $request->user();

        $status = $request->status;
        $sub_status = $request->sub_status;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $applicantname = $request->applicantname;
        $applicantidentity = $request->applicantidentity;

        $where = [
            'campaign_id' => $id
        ];
        $whereUser = [];

        if ($user->role == UserRole::TEAM_LEADER)
            $whereUser['assigned_leader'] = $user->id;

        if ($user->role == UserRole::AGENT)
            $whereUser['assigned_user'] = $user->id;

        if (!isset($status) || $status == 'Default')
            $status = '';

        if (!isset($sub_status))
            $sub_status = '';

        $where['progressStatus'] = $status;

        if ($sub_status != 'All')
            $where['progressSubStatus'] = $sub_status;

        $page = $request->input('page', 1); // Default page is 1
        $itemsPerPage = $request->input('itemsPerPage', 10); // Default items per page
        $skip = ($page - 1) * $itemsPerPage;

        if ((isset($start_date) && isset($end_date)) || isset($applicantname) || isset($applicantidentity)) {
            if (!isset($applicantname))
                $applicantname = '';

            if (!isset($applicantidentity))
                $applicantidentity = '';

            $builder = CampaignDetail::with(['leader', 'agent'])->where('applicantname', 'LIKE', "%{$applicantname}%")
                ->where('applicantidentity', 'LIKE', "%{$applicantidentity}%");

            if (isset($start_date) && isset($end_date)) {
                $builder = $builder->whereRaw(
                    "DATE_FORMAT(currentstatusdate, '%Y-%m-%d') BETWEEN ? AND ?",
                    [
                        $start_date,
                        $end_date,
                    ]
                );
            }
        } else {
            $builder = CampaignDetail::with(['leader', 'agent'])->where($where);
        }

        $builder = $builder->where($whereUser);
        $total = $builder->count();
        $list = $builder->skip($skip)->take($itemsPerPage)->get();

        return response()->json([
            "total" => $total,
            'list' => $list
        ]);
    }

    public function updateCampaignDetailStatus(Request $request, $id)
    {
        $request->validate([
            "progressStatus" => 'required',
            "progressSubStatus" => 'required',
        ]);

        $campaign_detail = CampaignDetail::find($id);
        $campaign_detail->progressStatus = $request->progressStatus;
        $campaign_detail->progressSubStatus = $request->progressSubStatus;
        $campaign_detail->campaignAgentRemark = $request->campaignAgentRemark;
        $campaign_detail->currentstatusdate = date('Y-m-d');

        $campaign_detail->save();

        return response()->json([
            "status" => "success",
            "campaign_detail" => $campaign_detail
        ]);
    }

    public function updateCampaignDetailRefNumber(Request $request, $id)
    {
        $request->validate([
            "ref_no" => 'required',
        ]);

        $campaign_detail = CampaignDetail::find($id);
        $campaign_detail->ref_no = $request->ref_no;
        $campaign_detail->progressStatus = 'submission';
        $campaign_detail->progressSubStatus = 'submission';

        $campaign_detail->save();

        return response()->json([
            "status" => "success",
        ]);
    }

    public function getCampaignDetailInfo($id)
    {
        $campaign_detail = CampaignDetail::with(['leader', 'agent'])->find($id);

        return response()->json([
            "status" => "success",
            "campaign_detail" => $campaign_detail
        ]);
    }

    public function getUnassignedCount(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role == UserRole::ADMIN) {
            $all = CampaignDetail::where('campaign_id', $id)->count();
            $unassigned = CampaignDetail::where('campaign_id', $id)->whereNull('assigned_leader')->count();
        } else {
            $where = [
                'campaign_id' => $id,
                'assigned_leader' => $user->id
            ];
            $all = CampaignDetail::where($where)->count();

            $unassigned = CampaignDetail::where($where)->whereNull('assigned_user')->count();
        }

        return response()->json([
            "status" => "success",
            "total_count" => $all,
            "unassigned_count" => $unassigned
        ]);
    }

    public function getUnassignedCountWithFilter(Request $request, $id)
    {
        $user = $request->user();
        $all_builder = $this->getFilter($request);
        $unassigned_builder = $this->getFilter($request);
        if ($user->role == UserRole::ADMIN) {
            $all_builder = $all_builder->where('campaign_id', $id);
            $unassigned_builder = $unassigned_builder->where('campaign_id', $id)->whereNull('assigned_leader');
        } else {
            $where = [
                'campaign_id' => $id,
                'assigned_leader' => $user->id
            ];
            $all_builder = $all_builder->where($where);
            $unassigned_builder = $unassigned_builder->where($where)->whereNull('assigned_user');
        }

        if ($request->category == -2) {
            $all = $all_builder->where(function ($query) {
                $query->whereNull('progressStatus')
                    ->orWhere('progressStatus', '');
            })->count();
            $unassigned = $unassigned_builder->where(function ($query) {
                $query->whereNull('progressStatus')
                    ->orWhere('progressStatus', '');
            })->count();
        } else {
            $all = $all_builder->count();
            $unassigned = $unassigned_builder->count();
        }

        return response()->json([
            "status" => "success",
            "total_count" => $all,
            "unassigned_count" => $unassigned
        ]);
    }
    public function getFilter(Request $request)
    {
        $query = CampaignDetail::query();

        if ($request->filled('applicanttypename')) {
            $query->where('applicanttypename', 'like', '%' . trim($request->applicanttypename) . '%');
        }
        if ($request->filled('applicantidentity')) {
            $query->where('applicantidentity', 'like', trim($request->applicantidentity) . '%');
        }
        if ($request->filled('applicantcompany')) {
            $query->where('applicantcompany', 'like', '%' . trim($request->applicantcompany) . '%');
        }
        if ($request->filled('applicantbusinessregistrationnumber')) {
            $query->where('applicantbusinessregistrationnumber', 'like', '%' . trim($request->applicantbusinessregistrationnumber) . '%');
        }
        if ($request->filled('applicantname')) {
            $query->where('applicantname', 'like', '%' . trim($request->applicantname) . '%');
        }
        if ($request->filled('racename') && $request->racename != 'All') {
            $query->where('racename', '=', trim($request->racename));
        }
        if ($request->filled('applicantmobile')) {
            $query->where('applicantmobile', 'like', '%' . trim($request->applicantmobile) . '%');
        }
        if ($request->filled('applicantfax')) {
            $query->where('applicantfax', 'like', '%' . trim($request->applicantfax) . '%');
        }
        if ($request->filled('applicantaddress1')) {
            $query->where('applicantaddress1', 'like', '%' . trim($request->applicantaddress1) . '%');
        }
        if ($request->filled('applicantaddress2')) {
            $query->where('applicantaddress2', 'like', '%' . trim($request->applicantaddress2) . '%');
        }
        if ($request->filled('applicantaddress3')) {
            $query->where('applicantaddress3', 'like', '%' . trim($request->applicantaddress3) . '%');
        }
        if ($request->filled('applicantpostcode')) {
            $query->where('applicantpostcode', 'like', '%' . trim($request->applicantpostcode) . '%');
        }
        if ($request->filled('applicantcity')) {
            $query->where('applicantcity', 'like', trim($request->applicantcity) . '%');
        }
        if ($request->filled('applicantstate')) {
            $query->where('applicantstate', 'like', trim($request->applicantstate) . '%');
        }
        if ($request->filled('applicantemail')) {
            $query->where('applicantemail', 'like', '%' . trim($request->applicantemail) . '%');
        }
        if ($request->filled('installationaddress1')) {
            $query->where('installationaddress1', 'like', '%' . $request->installationaddress1 . '%');
        }
        if ($request->filled('installationaddress2')) {
            $query->where('installationaddress2', 'like', '%' . $request->installationaddress2 . '%');
        }
        if ($request->filled('installationaddress3')) {
            $query->where('installationaddress3', 'like', '%' . $request->installationaddress3 . '%');
        }
        if ($request->filled('installationpostcode')) {
            $query->where('installationpostcode', 'like', '%' . $request->installationpostcode . '%');
        }
        if ($request->filled('installationcity')) {
            $query->where('installationcity', 'like', '%' . $request->installationcity . '%');
        }
        if ($request->filled('installationstate')) {
            $query->where('installationstate', 'like', '%' . $request->installationstate . '%');
        }
        if ($request->filled('installationpropertytype')) {
            $query->where('installationpropertytype', 'like', '%' . $request->installationpropertytype . '%');
        }
        if ($request->filled('installationcontactperson')) {
            $query->where('installationcontactperson', 'like', '%' . $request->installationcontactperson . '%');
        }
        if ($request->filled('installationcontactnumber')) {
            $query->where('installationcontactnumber', 'like', '%' . $request->installationcontactnumber . '%');
        }
        if ($request->filled('billingaddress1')) {
            $query->where('billingaddress1', 'like', '%' . $request->billingaddress1 . '%');
        }
        if ($request->filled('billingaddress2')) {
            $query->where('billingaddress2', 'like', '%' . $request->billingaddress2 . '%');
        }
        if ($request->filled('billingaddress3')) {
            $query->where('billingaddress3', 'like', '%' . $request->billingaddress3 . '%');
        }
        if ($request->filled('billingpostcode')) {
            $query->where('billingpostcode', 'like', '%' . $request->billingpostcode . '%');
        }
        if ($request->filled('billingcity')) {
            $query->where('billingcity', 'like', '%' . $request->billingcity . '%');
        }
        if ($request->filled('billingstate')) {
            $query->where('billingstate', 'like', '%' . $request->billingstate . '%');
        }
        if ($request->filled('productgroup')) {
            $query->where('productgroup', 'like', '%' . $request->productgroup . '%');
        }
        if ($request->filled('productname')) {
            $query->where('productname', 'like', '%' . trim($request->productname) . '%');
        }

        // For category and sub-category
        if ($request->filled('category') && $request->category != -1 && $request->category != -2) {
            $category = Category::find($request->category);
            if ($category != null) {
                $query->where('progressStatus', '=', $category->name);
            }

            if ($request->filled('sub_category') && $request->sub_category != -1) {
                $subCategory = Category::find($request->sub_category);
                if ($subCategory != null) {
                    $query->where('progressSubStatus', '=', $subCategory->name);
                }
            }
        }

        return $query;
    }

    public function assign(Request $request, $id)
    {
        $user = $request->user();
        $isAdmin = $user->role == UserRole::ADMIN;

        $request->validate([
            "leader" => 'required',
            "method" => 'required',
            "amount" => 'required'
        ]);

        $where = [
            'campaign_id' => $id,
            'assigned_leader' => $user->id
        ];

        if ($request->method == 'Normal') {
            if ($isAdmin)
                CampaignDetail::where(['campaign_id' => $id])
                    ->whereNull('assigned_leader')
                    ->limit($request->amount)
                    ->update([
                        'assigned_date' => date('Y-m-d'),
                        'assigned_leader' => $request->leader
                    ]);
            else
                CampaignDetail::where($where)
                    ->whereNull('assigned_user')
                    ->limit($request->amount)
                    ->update([
                        'assigned_date' => date('Y-m-d'),
                        'assigned_user' => $request->leader
                    ]);
        } else {
            if ($isAdmin)
                CampaignDetail::where(['campaign_id' => $id])
                    ->whereNull('assigned_leader')
                    ->inRandomOrder()
                    ->limit($request->amount)
                    ->update([
                        'assigned_date' => date('Y-m-d'),
                        'assigned_leader' => $request->leader
                    ]);
            else
                CampaignDetail::where($where)
                    ->whereNull('assigned_user')
                    ->limit($request->amount)
                    ->inRandomOrder()
                    ->update([
                        'assigned_date' => date('Y-m-d'),
                        'assigned_user' => $request->leader
                    ]);
        }

        return response()->json([
            "status" => "success"
        ]);
    }

    public function assignWithFilter(Request $request, $id)
    {
        $user = $request->user();
        $isAdmin = $user->role == UserRole::ADMIN;

        $request->validate([
            "leader" => 'required',
            "method" => 'required',
            "amount" => 'required'
        ]);

        $where = [
            'campaign_id' => $id,
            'assigned_leader' => $user->id
        ];

        $whereFilter = $this->getFilter($request);

        if ($request->method == 'Normal') {
            if ($request->category == -2) {
                if ($isAdmin)
                    $whereFilter->where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        // ->where($whereFilter)
                        ->where(function ($query) {
                            $query->whereNull('progressStatus')
                                ->orWhere('progressStatus', '');
                        })
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_leader' => $request->leader
                        ]);
                else
                    $whereFilter->where($where)
                        ->whereNull('assigned_user')
                        // ->where($whereFilter)
                        ->where(function ($query) {
                            $query->whereNull('progressStatus')
                                ->orWhere('progressStatus', '');
                        })
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_user' => $request->leader
                        ]);
            } else {

                if ($isAdmin)
                    $whereFilter->where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        // ->where($whereFilter)
                        ->limit($request->amount)
                        ->update([
                            'assigned_leader' => $request->leader
                        ]);
                else
                    $whereFilter->where($where)
                        ->whereNull('assigned_user')
                        // ->where($whereFilter)
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_user' => $request->leader
                        ]);
            }
        } else {
            if ($request->category == -2) {
                if ($isAdmin)
                    $whereFilter->where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        // ->where($whereFilter)
                        ->where(function ($query) {
                            $query->whereNull('progressStatus')
                                ->orWhere('progressStatus', '');
                        })
                        ->inRandomOrder()
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_leader' => $request->leader
                        ]);
                else
                    $whereFilter->where($where)
                        ->whereNull('assigned_user')
                        // ->where($whereFilter)
                        ->where(function ($query) {
                            $query->whereNull('progressStatus')
                                ->orWhere('progressStatus', '');
                        })
                        ->limit($request->amount)
                        ->inRandomOrder()
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_user' => $request->leader
                        ]);
            } else {
                if ($isAdmin)
                    $whereFilter->where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        // ->where($whereFilter)
                        ->inRandomOrder()
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_leader' => $request->leader
                        ]);
                else
                    $whereFilter->where($where)
                        ->whereNull('assigned_user')
                        // ->where($whereFilter)
                        ->limit($request->amount)
                        ->inRandomOrder()
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_user' => $request->leader
                        ]);
            }
        }

        return response()->json([
            "status" => "success"
        ]);
    }

    public function updateCampaign(Request $request)
    {
        $this->_validate($request);
        $request->validate(["id" => "integer"]);

        $campaign = $this->_instantiate($request);
        $campaign->id = $request->id;
        $campaign->save();

        return response()->json([
            "status" => "success"
        ]);
    }

    public function activateCampaign(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        $campaign->status = $campaign->status == 'active' ?
            'inactive' :
            'active';

        $campaign->save();
        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }

    public function deleteCampaign(Request $request, $id)
    {
        Campaign::find($id)->delete();

        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }

    public function getRacenames()
    {
        $races = CampaignDetail::select('racename')
            ->distinct()
            ->pluck('racename')
            ->toArray();

        return response()->json([
            "racenames" => $races
        ]);
    }

    public function getLeadProgress(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role == UserRole::AGENT)
            return response()->json([
                "status" => [],
                "lead_progress" => []
            ]);

        $total_status = "total_leads";
        $filtered_total_status = "filtered_total_leads";
        $average = "Average";
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!isset($start_date))
            $start_date = '2000-01-01';

        if (!isset($end_date))
            $end_date = date('Y-m-d');

        $status = CampaignDetail::select('progressStatus')
            ->where(["campaign_id" => $id])
            ->groupBy('progressStatus')
            ->pluck("progressStatus")
            ->toArray();

        // $status = Category::whereNull("parent_id")
        //     ->where("type", CategoryType::PROGRESS->value)
        //     ->pluck('name')
        //     ->toArray();

        if ($user->role == UserRole::ADMIN) {
            $leaders = User::where([
                "role" => UserRole::TEAM_LEADER->value
            ])->pluck("username", "id")->toArray();

            $statusCounts = CampaignDetail::selectRaw(
                'progressStatus, assigned_leader, count(*) as count'
            )
                ->where(["campaign_id" => $id])
                ->whereRaw('DATE(currentstatusdate) >=? AND DATE(currentstatusdate) <=?', [$start_date, $end_date])
                ->groupBy('progressStatus', 'assigned_leader')
                ->get();
        } else {
            $leaders = User::where([
                "role" => UserRole::AGENT->value,
                "team_leader" => $user->id
            ])->pluck("username", "id")->toArray();

            $leaderIds = User::where([
                "role" => UserRole::AGENT->value,
                "team_leader" => $user->id
            ])->pluck("id")->toArray();

            $statusCounts = CampaignDetail::selectRaw(
                'progressStatus, assigned_user, count(*) as count'
            )
                ->where(["campaign_id" => $id])
                ->whereIn('assigned_user', $leaderIds)
                ->whereRaw('DATE(currentstatusdate) >=? AND DATE(currentstatusdate) <=?', [$start_date, $end_date])
                ->groupBy('progressStatus', 'assigned_user')
                ->get();
        }


        $allStatusCount = [];
        $result = [];
        foreach ($statusCounts as $record) {
            if ($user->role == UserRole::ADMIN)
                $result[$record->assigned_leader][$record->progressStatus] = $record->count;
            else
                $result[$record->assigned_user][$record->progressStatus] = $record->count;
        }

        foreach ($leaders as $uid => $username) {
            $allStatusCount[$username][$total_status] = 0;
            $allStatusCount[$username][$filtered_total_status] = 0;
            foreach ($status as $state) {

                if (!isset($result[$uid][$state]))
                    $allStatusCount[$username][$state] = 0;
                else
                    $allStatusCount[$username][$state] = $result[$uid][$state];

                $allStatusCount[$username][$total_status] += $allStatusCount[$username][$state];
                if ($state != '')
                    $allStatusCount[$username][$filtered_total_status] += $allStatusCount[$username][$state];
            }
        }

        $allStatusCount[$average][$total_status] = 0;
        foreach ($leaders as $uid => $username) {
            foreach ($status as $state) {
                if (!isset($allStatusCount[$average][$state]))
                    $allStatusCount[$average][$state] = 0;
                if (!isset($allStatusCount[$average][$filtered_total_status]))
                    $allStatusCount[$average][$filtered_total_status] = 0;

                $allStatusCount[$average][$state] += $allStatusCount[$username][$state];
                $allStatusCount[$average][$total_status] += $allStatusCount[$username][$state];
                if ($state != '')
                    $allStatusCount[$average][$filtered_total_status] += $allStatusCount[$username][$state];
            }
        }


        $lead_progress = [];
        foreach ($allStatusCount as $username => $userStatusCount) {
            $userStatusCount["name"] = $username;
            $lead_progress[] = $userStatusCount;
        }

        return response()->json([
            "status" => $status,
            "lead_progress" => $lead_progress
        ]);
    }

    function getTotalFilteredCount(Request $request, $id)
    {
        $user = $request->user();
        $builder = CampaignDetail::where("campaign_id", $id)
            // ->whereDate('currentstatusdate', date('Y-m-d'))
            // ->where('currentstatusdate', '>=', date('Y-m-d') . ' 00:00:00')
            ->whereRaw('DATE(currentstatusdate) = CURDATE() ')
            ->where('progressStatus', '!=', '');

        if ($user->role == UserRole::TEAM_LEADER) {
            $builder = $builder->where(['assigned_leader' => $user->id]);
        } else if ($user->role == UserRole::AGENT) {
            $builder = $builder->where(['assigned_user' => $user->id]);
        }

        $total = $builder->count();

        return response()->json([
            "total" => $total
        ]);
    }
}
