<?php
$userRoleArr = ['admin', 'admin'];
$uniqueRoles = array_unique(is_array($userRoleArr) && isset($userRoleArr[0]) && is_array($userRoleArr[0]) ? array_column($userRoleArr, "name") : $userRoleArr);
print_r($uniqueRoles);
