@extends('admin.layout')
@section('content')
    <div class="pd-20" style="padding-top:20px;">
        <p class="f-20 text-success">Area Statistics</p>
        <table class="table table-border table-bordered table-bg mt-20">
            <thead>
            <tr>
                <th colspan="5" scope="col">Area | Order Numbers | Rate Of Number | Amount | Rate Of Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($areas as $k=>$v): ?>
            <tr>
                <th width="200"><?php echo e($k); ?></th>
                <td><?php echo e($v['order_num']); ?></td>
                <td><?php echo e(number_format($v['order_num']/$orderNum*100, 2, '.', '')); ?> %</td>
                <td><?php echo e(number_format($v['amount'], 2, '.', '')); ?></td>
                <td><?php echo e(number_format($v['amount']/$orderAmount*100, 2, '.', '')); ?> %</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th width="200">Total</th>
                <td><?php echo e($orderNum); ?></td>
                <td>100 %</td>
                <td><?php echo e($orderAmount); ?></td>
                <td>100 %</td>
            </tr>
            </tbody>
        </table>
    </div>
    <footer class="footer"></footer>
@endsection