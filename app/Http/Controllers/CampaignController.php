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

            $builder = CampaignDetail::with(['leader', 'agent'])->where('applicantname',  'LIKE', "%{$applicantname}%")
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

        if ($user->role == UserRole::ADMIN) {
            $all_builder = CampaignDetail::where('campaign_id', $id);
            $unassigned_builder = CampaignDetail::where('campaign_id', $id)->whereNull('assigned_leader');
        } else {
            $where = [
                'campaign_id' => $id,
                'assigned_leader' => $user->id
            ];
            $all_builder = CampaignDetail::where($where);
            $unassigned_builder = CampaignDetail::where($where)->whereNull('assigned_user');
        }

        $where = $this->getFilter($request);

        if ($request->category == -2) {
            $all = $all_builder->where($where)->where(function ($query) {
                $query->whereNull('progressStatus')
                    ->orWhere('progressStatus', '');
            })->count();
            $unassigned = $unassigned_builder->where($where)->where(function ($query) {
                $query->whereNull('progressStatus')
                    ->orWhere('progressStatus', '');
            })->count();
        } else {
            $all = $all_builder->where($where)->count();
            $unassigned = $unassigned_builder->where($where)->count();
        }

        return response()->json([
            "status" => "success",
            "total_count" => $all,
            "unassigned_count" => $unassigned
        ]);
    }

    public function getFilter(Request $request)
    {
        $where = [];
        if (isset($request->applicanttypename) && $request->applicanttypename != '') {
            $where["applicanttypename"] = $request->applicanttypename;
        }
        if (isset($request->applicantidentity) && $request->applicantidentity != '') {
            $where["applicantidentity"] = $request->applicantidentity;
        }
        if (isset($request->applicantcompany) && $request->applicantcompany != '') {
            $where["applicantcompany"] = $request->applicantcompany;
        }
        if (isset($request->applicantbusinessregistrationnumber) && $request->applicantbusinessregistrationnumber != '') {
            $where["applicantbusinessregistrationnumber"] = $request->applicantbusinessregistrationnumber;
        }
        if (isset($request->applicantname) && $request->applicantname != '') {
            $where["applicantname"] = $request->applicantname;
        }
        if (isset($request->racename) && $request->racename != 'All') {
            $where["racename"] = $request->racename;
        }
        if (isset($request->applicantmobile) && $request->applicantmobile != '') {
            $where["applicantmobile"] = $request->applicantmobile;
        }
        if (isset($request->applicantfax) && $request->applicantfax != '') {
            $where["applicantfax"] = $request->applicantfax;
        }
        if (isset($request->applicantaddress1) && $request->applicantaddress1 != '') {
            $where["applicantaddress1"] = $request->applicantaddress1;
        }
        if (isset($request->applicantaddress2) && $request->applicantaddress2 != '') {
            $where["applicantaddress2"] = $request->applicantaddress2;
        }
        if (isset($request->applicantaddress3) && $request->applicantaddress3 != '') {
            $where["applicantaddress3"] = $request->applicantaddress3;
        }
        if (isset($request->applicantpostcode) && $request->applicantpostcode != '') {
            $where["applicantpostcode"] = $request->applicantpostcode;
        }
        if (isset($request->applicantcity) && $request->applicantcity != '') {
            $where["applicantcity"] = $request->applicantcity;
        }
        if (isset($request->applicantstate) && $request->applicantstate != '') {
            $where["applicantstate"] = $request->applicantstate;
        }
        if (isset($request->applicantemail) && $request->applicantemail != '') {
            $where["applicantemail"] = $request->applicantemail;
        }
        if (isset($request->installationaddress1) && $request->installationaddress1 != '') {
            $where["installationaddress1"] = $request->installationaddress1;
        }
        if (isset($request->installationaddress2) && $request->installationaddress2 != '') {
            $where["installationaddress2"] = $request->installationaddress2;
        }
        if (isset($request->installationaddress3) && $request->installationaddress3 != '') {
            $where["installationaddress3"] = $request->installationaddress3;
        }
        if (isset($request->installationpostcode) && $request->installationpostcode != '') {
            $where["installationpostcode"] = $request->installationpostcode;
        }
        if (isset($request->installationcity) && $request->installationcity != '') {
            $where["installationcity"] = $request->installationcity;
        }
        if (isset($request->installationstate) && $request->installationstate != '') {
            $where["installationstate"] = $request->installationstate;
        }
        if (isset($request->installationpropertytype) && $request->installationpropertytype != '') {
            $where["installationpropertytype"] = $request->installationpropertytype;
        }
        if (isset($request->installationcontactperson) && $request->installationcontactperson != '') {
            $where["installationcontactperson"] = $request->installationcontactperson;
        }
        if (isset($request->installationcontactnumber) && $request->installationcontactnumber != '') {
            $where["installationcontactnumber"] = $request->installationcontactnumber;
        }
        if (isset($request->billingaddress1) && $request->billingaddress1 != '') {
            $where["billingaddress1"] = $request->billingaddress1;
        }
        if (isset($request->billingaddress2) && $request->billingaddress2 != '') {
            $where["billingaddress2"] = $request->billingaddress2;
        }
        if (isset($request->billingaddress3) && $request->billingaddress3 != '') {
            $where["billingaddress3"] = $request->billingaddress3;
        }
        if (isset($request->billingpostcode) && $request->billingpostcode != '') {
            $where["billingpostcode"] = $request->billingpostcode;
        }
        if (isset($request->billingcity) && $request->billingcity != '') {
            $where["billingcity"] = $request->billingcity;
        }
        if (isset($request->billingstate) && $request->billingstate != '') {
            $where["billingstate"] = $request->billingstate;
        }
        if (isset($request->productgroup) && $request->productgroup != '') {
            $where["productgroup"] = $request->productgroup;
        }
        if (isset($request->productname) && $request->productname != '') {
            $where["productname"] = $request->productname;
        }

        if (isset($request->category) && $request->category != -1 && $request->category != -2) {
            $category = Category::find($request->category);
            if ($category != null) {
                $where["progressStatus"] = $category->name;
            }

            if (isset($request->sub_category) && $request->sub_category != -1) {
                $category = Category::find($request->sub_category);
                if ($category != null) {
                    $where["progressSubStatus"] = $category->name;
                }
            }
        }

        return $where;
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
                    CampaignDetail::where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        ->where($whereFilter)
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
                    CampaignDetail::where($where)
                        ->whereNull('assigned_user')
                        ->where($whereFilter)
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
                    CampaignDetail::where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        ->where($whereFilter)
                        ->limit($request->amount)
                        ->update([
                            'assigned_leader' => $request->leader
                        ]);
                else
                    CampaignDetail::where($where)
                        ->whereNull('assigned_user')
                        ->where($whereFilter)
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_user' => $request->leader
                        ]);
            }
        } else {
            if ($request->category == -2) {
                if ($isAdmin)
                    CampaignDetail::where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        ->where($whereFilter)
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
                    CampaignDetail::where($where)
                        ->whereNull('assigned_user')
                        ->where($whereFilter)
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
                    CampaignDetail::where(['campaign_id' => $id])
                        ->whereNull('assigned_leader')
                        ->where($whereFilter)
                        ->inRandomOrder()
                        ->limit($request->amount)
                        ->update([
                            'assigned_date' => date('Y-m-d'),
                            'assigned_leader' => $request->leader
                        ]);
                else
                    CampaignDetail::where($where)
                        ->whereNull('assigned_user')
                        ->where($whereFilter)
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
            ->whereDate('currentstatusdate', date('Y-m-d'))
            ->where('progressStatus', '!=', '');

        if ($user->role == UserRole::TEAM_LEADER) {
            $builder = $builder->where(['assigned_leader' => $user->id]);
        } else if ($user->role == UserRole::AGENT) {
            $builder = $builder->where(['assigned_agent' => $user->id]);
        }

        $total = $builder->count();

        return response()->json([
            "total" => $total
        ]);
    }
}
