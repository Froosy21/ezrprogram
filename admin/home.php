<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
  <nav class="sidebar">
    <ul>
      <li><a href="javascript:void(0)" onclick="loadPage('dashboard')">Dashboard</a></li>
      <li><a href="javascript:void(0)" onclick="loadPage('orders')">Orders</a></li>
      <li><a href="javascript:void(0)" onclick="loadPage('users')">User Management</a></li>
      <li><a href="javascript:void(0)" onclick="loadPage('admin_events')">Events</a></li>
      <li><a href="javascript:void(0)" onclick="loadPage('products')">Products</a></li>
      <li><a href="javascript:void(0)" onclick="loadPage('inventory')">Inventory</a></li>
    </ul>
  </nav>
  <div id="content"></div>
  <script>
    function loadPage(page) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', page + '.php', true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          document.getElementById('content').innerHTML = xhr.responseText;
        }
      };
      xhr.send();
    }
  </script>
</body>
</html>