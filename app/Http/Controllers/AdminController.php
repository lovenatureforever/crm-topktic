<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\CampaignDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Category;

class AdminController extends Controller
{


    public function getAllCampaignCategories()
    {
        $data = $this->_getAllCampaignCategories();

        return response()->json([
            "status" => "success",
            "data" => $data,
        ]);
    }

    public function createCampaignCategory(Request $request)
    {

        $request->validate([
            "name" => 'required'
        ]);

        $category = new Category([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'type' => CategoryType::CAMPAIGN->value
        ]);

        if ($category->save()) {

            $data = $this->_getAllCampaignCategories();
            return response()->json([
                "status" => "success",
                "data" => $data,
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function updateCampaignCategory(Request $request, $id)
    {

        $request->validate([
            "name" => 'required'
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;

        if ($category->save()) {

            $data = $this->_getAllCampaignCategories();
            return response()->json([
                "status" => "success",
                "data" => $data,
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function deleteCampaignCategory(Request $request, $id)
    {
        Category::find($id)->delete();

        $data = $this->_getAllCampaignCategories();

        return response()->json([
            "status" => "success",
            "data" => $data,
        ]);
    }

    private function _getAllCampaignCategories()
    {
        $type = CategoryType::CAMPAIGN->value;
        $categories = Category::whereNull("parent_id")
            ->where("type", $type)
            ->get();

        $sub_categories = Category::whereNotNull("parent_id")
            ->where("type", $type)
            ->get();

        return [
            "categories" => $categories,
            "sub_categories" => $sub_categories,
        ];
    }

    public function getAllProgressCategories()
    {
        $data = $this->_getAllProgressCategories();

        return response()->json([
            "status" => "success",
            "data" => $data,
        ]);
    }

    public function createProgressCategory(Request $request)
    {

        $request->validate([
            "name" => 'required'
        ]);

        $category = new Category([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'type' => CategoryType::PROGRESS->value
        ]);

        if ($category->save()) {

            $data = $this->_getAllProgressCategories();
            return response()->json([
                "status" => "success",
                "data" => $data,
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function updateProgressCategory(Request $request, $id)
    {

        $request->validate([
            "name" => 'required'
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;

        if ($category->save()) {

            $data = $this->_getAllProgressCategories();
            return response()->json([
                "status" => "success",
                "data" => $data,
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function deleteProgressCategory(Request $request, $id)
    {
        Category::find($id)->delete();
        Category::where(["parent_id" => $id])->delete();

        $data = $this->_getAllProgressCategories();

        return response()->json([
            "status" => "success",
            "data" => $data,
        ]);
    }

    private function _getAllProgressCategories()
    {
        $type = CategoryType::PROGRESS->value;
        $categories = Category::whereNull("parent_id")
            ->where("type", $type)
            ->get();

        $sub_categories = Category::whereNotNull("parent_id")
            ->where("type", $type)
            ->get();

        return [
            "categories" => $categories,
            "sub_categories" => $sub_categories,
        ];
    }

    /* public function exportCsv(Request $request)
    {
        $chunk = 500; // Number of records per chunk
        $output = fopen('php://stdout', 'wb+'); // Open a stream to StdOut

        // Write the CSV headers
        fputcsv($output, ['ID', 'Campaign', 'Leader', 'Agent', 'Status', 'Sub Status', 'Applicant Name', 'Applicant Mobile', 'Applicant Other Phone']);

        $leaderId = request('leader');
        $agentId = request('agent'); // can be "all"
        $status = request('status');
        $subStatus = request('sub_status'); // can be "all"

        DB::table('campaign_details')
            ->leftJoin('users as leaders', 'campaign_details.assigned_leader', '=', 'leaders.id')
            ->leftJoin('users as agents', 'campaign_details.assigned_user', '=', 'agents.id')
            ->leftJoin('categories as ps', 'campaign_details.progressStatus', '=', 'ps.id')
            ->leftJoin('categories as pss', 'campaign_details.progressSubStatus', '=', 'pss.id')
            ->where('campaign_details.assigned_leader', $leaderId)
            ->where('campaign_details.progressStatus', $status)
            ->when($agentId !== 'all', fn($q) => $q->where('campaign_details.assigned_user', $agentId))
            ->when($subStatus !== 'all', fn($q) => $q->where('campaign_details.progressSubStatus', $subStatus))
            ->orderBy('agents.username')
            ->select([
                'campaign_details.id',
                'campaign_details.campaign_id',
                'leaders.username as leader_name',
                'agents.username as agent_name',
                'ps.name as progress_status_name',
                'pss.name as progress_sub_status_name',
                'campaign_details.applicantname',
                'campaign_details.applicantmobile',
                'campaign_details.applicantotherphone',
            ])
            ->chunk($chunk, function ($rows) use ($output) {
                foreach ($rows as $row) {
                    fputcsv($output, [
                        $row->id,
                        $row->campaign_id,
                        $row->leader_name,
                        $row->agent_name,
                        $row->progressStatus,
                        $row->progressSubStatus,
                        $row->applicantname,
                        $row->applicantmobile,
                        $row->applicantotherphone,
                    ]);
                }
            });
    } */

    public function exportCsv(Request $request)
    {
        $chunk = 500;

        $leaderId = $request->input('leader');
        $agentId = $request->input('agent');
        $status = $request->input('status');
        $subStatus = $request->input('sub-status');

        $response = new StreamedResponse(function () use ($chunk, $leaderId, $agentId, $status, $subStatus) {
            $output = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($output, [
                'ID',
                'Campaign',
                'Leader',
                'Agent',
                'Status',
                'Sub Status',
                'Applicant Name',
                'Applicant Mobile',
                'Applicant Other Phone',
                'Applicantidentity',
                'Applicantaddress1',
                'Applicantaddress2',
                'Applicantaddress3',
                'Applicantpostcode',
                'Applicantcity',
                'Applicantstate',
                'Billingaddress1',
                'Productname',
            ]);

            DB::table('campaign_details')
                ->leftJoin('users as leaders', 'campaign_details.assigned_leader', '=', 'leaders.id')
                ->leftJoin('users as agents', 'campaign_details.assigned_user', '=', 'agents.id')
                ->where('campaign_details.assigned_leader', $leaderId)
                ->where('campaign_details.progressStatus', $status)
                ->when($agentId !== 'all', fn($q) => $q->where('campaign_details.assigned_user', $agentId))
                ->when($subStatus !== 'all', fn($q) => $q->where('campaign_details.progressSubStatus', $subStatus))
                ->orderBy('agents.username')
                ->select([
                    'campaign_details.id',
                    'campaign_details.campaign_id',
                    'leaders.username as leader_name',
                    'agents.username as agent_name',
                    'campaign_details.progressStatus',
                    'campaign_details.progressSubStatus',
                    'campaign_details.applicantname',
                    'campaign_details.applicantmobile',
                    'campaign_details.applicantotherphone',
                    'campaign_details.applicantidentity',
                    'campaign_details.applicantaddress1',
                    'campaign_details.applicantaddress2',
                    'campaign_details.applicantaddress3',
                    'campaign_details.applicantpostcode',
                    'campaign_details.applicantcity',
                    'campaign_details.applicantstate',
                    'campaign_details.billingaddress1',
                    'campaign_details.productname',
                ])
                ->chunk($chunk, function ($rows) use ($output) {
                    foreach ($rows as $row) {
                        fputcsv($output, [
                            $row->id,
                            $row->campaign_id,
                            $row->leader_name,
                            $row->agent_name,
                            $row->progressStatus,
                            $row->progressSubStatus,
                            $row->applicantname,
                            $row->applicantmobile,
                            $row->applicantotherphone,
                            $row->applicantidentity,
                            $row->applicantaddress1,
                            $row->applicantaddress2,
                            $row->applicantaddress3,
                            $row->applicantpostcode,
                            $row->applicantcity,
                            $row->applicantstate,
                            $row->billingaddress1,
                            $row->productname,
                        ]);
                    }
                });

            fclose($output);
        });

        $filename = 'campaign_export_' . now()->format('Ymd_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
