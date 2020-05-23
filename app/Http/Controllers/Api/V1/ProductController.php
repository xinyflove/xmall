<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $_image_host = 'http://127.0.0.1:8000';

    /**
     * 获取商品列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filter['pageSize'] = intval($request->input('pageSize', 20));
        $filter['pageNum'] = intval($request->input('pageNum', 1));
        $filter['keyword'] = $request->input('keyword', '');
        $filter['cat_id'] = $request->input('cat_id', 0);
        $filter['orderBy'] = $request->input('orderBy', 'default');

        $where[] = ['status', '=', 1];
        if (!empty($filter['keyword']))
        {
            $where[] = ['title', 'like', "%{$filter['keyword']}%"];
        }
        if (!empty($filter['cat_id']))
        {
            $where[] = ['cat_id', '=', $filter['cat_id']];
        }
        $order_by = (new Product)->getOrderBy($filter['orderBy']);
        $offset = ($filter['pageNum'] - 1) * $filter['pageSize'];

        $product = Product::withoutGlobalScope('avaiable')->where($where);
        $total = $product->count();
        $list = $product->orderBy($order_by[0], $order_by[1])
            ->offset($offset)->limit($filter['pageSize'])->get();
        foreach ($list as &$item)
        {
            $item['image_host'] = $this->_image_host;
        }
        unset($item);

        $pages = ceil($total/$filter['pageSize']);
        $hasPreviousPage = $filter['pageNum'] > 1 ? true : false;
        $hasNextPage = $pages > $filter['pageNum'] ? true : false;
        $prePage = $hasPreviousPage ? $filter['pageNum'] - 1 : 1;
        $nextPage = $hasNextPage ? $filter['pageNum'] + 1 : $pages;

        $data = [
            'total' => $total,
            'list' => $list,
            'pages' => $pages,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'pageNum' => $filter['pageNum'],
        ];

        return success_json($data);
    }

    /**
     * 获取商品详细信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $productId = $request->input('productId');
        $product = Product::where('status', 1)->find($productId);
        
        if (!$product)
        {
            return error_json(10300);
        }

        $product->image_host = $this->_image_host;

        return success_json($product);
    }
}
