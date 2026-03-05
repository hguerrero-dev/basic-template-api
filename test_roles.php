<?php
$userRoleArr = [['id' => 1, 'name' => 'admin'], ['id' => 2, 'name' => 'admin']];
$uniqueRoles = is_array($userRoleArr) ? array_unique(array_column($userRoleArr, "name")) : [];
print_r($uniqueRoles);
