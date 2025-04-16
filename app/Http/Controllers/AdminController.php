<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use Illuminate\Http\Request;

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
}
