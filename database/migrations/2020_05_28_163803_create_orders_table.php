<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->comment('订单号');
            $table->integer('user_id')->comment('用户ID');
            $table->string('status', 30)->comment('订单状态');
            $table->string('cancel_status', 30)->default('NO_APPLY_CANCEL')->comment('取消订单状态');
            $table->string('cancel_reason')->comment('取消订单原因');
            $table->string('pay_type', 10)->default('online')->comment('支付类型');
            $table->decimal('payment', 20, 2)->default('0.00')->comment('实付金额,订单最终总额');
            $table->decimal('points_fee', 20, 2)->default('0.00')->comment('积分抵扣金额');
            $table->decimal('total_fee', 20, 2)->default('0.00')->comment('各子订单中商品price * num的和，不包括任何优惠信息');
            $table->decimal('post_fee', 20, 2)->default('0.00')->comment('邮费');
            $table->decimal('payed_fee', 20, 2)->default('0.00')->comment('已支付金额(包含红包支付的金额)');
            $table->decimal('hongbao_fee', 20, 2)->default('0.00')->comment('红包支付金额');
            $table->string('user_hongbao_id')->comment('红包支付金额');
            $table->string('name', 50)->comment('收货人');
            $table->string('mobile', 20)->comment('手机号码');
            $table->string('tel', 20)->comment('固定电话');
            $table->string('zip', 10)->comment('邮编');
            $table->string('province', 20)->comment('所在省');
            $table->string('city', 20)->comment('所在市');
            $table->string('district', 20)->comment('所在县/市/区');
            $table->string('address', 50)->comment('详细地址');
            $table->unsignedInteger('pay_time')->default(0)->comment('付款时间');
            $table->unsignedInteger('consign_time')->default(0)->comment('卖家发货时间');
            $table->unsignedInteger('end_time')->default(0)->comment('结束时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
