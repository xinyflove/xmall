<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter['page_size'] = $request->input('page_size', 20);
        $filter['page_num'] = $request->input('page_num', 1);
        $filter['keyword'] = $request->input('keyword', '');
        $filter['cat_id'] = $request->input('cat_id', 0);
        $filter['order_by'] = $request->input('order_by', 'default');

        $where = [];
        if (!empty($filter['keyword']))
        {
            $where[] = ['title', 'like', "%{$filter['keyword']}%"];
        }
        if (!empty($filter['cat_id']))
        {
            $where[] = ['cat_id', '=', $filter['cat_id']];
        }
        $order_by = (new Product)->getOrderBy($filter['order_by']);
        $offset = ($filter['page_num'] - 1) * $filter['page_size'];

        $product = Product::withoutGlobalScope('avaiable')->where($where);
        $total = $product->count();
        $list = $product->orderBy($order_by[0], $order_by[1])
            ->offset($offset)->limit($filter['page_size'])->get();
        foreach ($list as &$item)
        {
            $item['image_host'] = 'http://127.0.0.1:8000';
        }
        unset($item);

        $data = [
            'total' => $total,
            'list' => $list,
        ];
        return success_json($data);
    }
}
