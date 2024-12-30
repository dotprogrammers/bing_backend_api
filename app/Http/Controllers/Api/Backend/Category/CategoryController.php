<?php

namespace App\Http\Controllers\Api\Backend\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\SkillJob;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $categories = Category::where('is_delete', 0);

        if ($search) {
            $categories->where('name', 'like', "%$search%");
        }

        $categories = $categories->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $category], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $category = Category::find($request->id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->save();

        return response()->json(['success' => true, 'data' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->is_delete = 1;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }


    // skill job controller

    public function getSkill(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $skills = SkillJob::where('is_delete', 0);

        if ($search) {
            $skills->where('name', 'like', "%$search%");
        }

        $skills = $skills->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $skills
        ], 200);
    }


    public function storeSkill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:skills,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $skill = new SkillJob();
        $skill->name = $request->name;
        $skill->save();

        return response()->json([
            'success' => true,
            'message' => 'skill created successfully',
            'data' => $skill
        ], 200);
    }



    public function updateSkill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $skill = SkillJob::find($request->id);
        if (!$skill) {
            return response()->json(['success' => false, 'message' => 'skill not found'], 404);
        }

        $skill->name = $request->name;
        $skill->save();

        return response()->json(['success' => true, 'data' => $skill], 200);
    }

    public function destroySkill($id)
    {
        $skill = SkillJob::find($id);
        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found'
            ], 404);
        }

        $skill->is_delete = 1;
        $skill->save();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully'
        ], 200);
    }


}
