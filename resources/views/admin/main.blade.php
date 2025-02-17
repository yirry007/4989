@extends('admin.layout')
@section('content')
<div class="pd-20" style="padding-top:20px;">
  <p class="f-20 text-success">Welcome to use H-ui.admin <span class="f-14">v2.3</span>Backend Template</p>
  <a class="btn btn-primary radius" href="<?php echo e(url('/admin/order_print_view')); ?>">Print Order</a>
  <table class="table table-border table-bordered table-bg mt-20">
    <thead>
      <tr>
        <th colspan="2" scope="col">Server Info</th>
      </tr>
    </thead>
    <tbody>
    <tr>
      <th width="200">Server OS </th>
      <td><?php echo php_uname(); ?></td>
    </tr>
    <tr>
      <td>Web Server </td>
      <td><?php echo php_sapi_name(); ?></td>
    </tr>
    <tr>
      <td>Server Host </td>
      <td><?php echo $_SERVER['HTTP_HOST']; ?></td>
    </tr>
    <tr>
      <td>Server IP </td>
      <td><?php echo GetHostByName($_SERVER['SERVER_NAME']); ?></td>
    </tr>
    <tr>
      <td>PHP Version </td>
      <td><?php echo PHP_VERSION; ?></td>
    </tr>
    <tr>
      <td>MySQL Version </td>
      <td><?php echo $mysqlVersion[0]->version; ?></td>
    </tr>
    <tr>
      <td>Timezone </td>
      <td><?php echo date_default_timezone_get(); ?></td>
    </tr>
    <tr>
      <td>Server time </td>
      <td><?php echo date("Y-m-d H:i:s"); ?></td>
    </tr>
    <tr>
      <td>Max Upload File Size </td>
      <td><?php echo get_cfg_var ("upload_max_filesize") ? get_cfg_var ("upload_max_filesize") : "Not allowed to upload"; ?></td>
    </tr>
    </tbody>
  </table>
</div>
<footer class="footer"></footer>
@endsection