<?php


function deleteUser(&$customers, $id) {
    foreach ($customers as $key => $customer) {
        if ($customer['id'] == $id ){
            unset($customers[$key]); // Xóa phần tử tại vị trí index đó
            return true;
        }
    }
    return false;
}

function createUser(&$customers,string $name,string $email,string $phone,string $address){
    $newId = count($customers) + 1;
    $customers[] = [
        "id"  => $newId,
        "name" => $name,
        "email" => $email,
        "phone" => $phone,
        "address" => $address 
    ];

    return $customers;
}

function updateUser(&$customers,$id, $name, $email, $phone,$address){
    foreach($customers as &$customer){
        if($customer['id'] == $id){
            $customer['name'] = $name;
            $customer['email'] = $email;
            $customer['phone'] = $phone;
            $customer['address'] = $address;
            return True;
        }
    }
    return False;
}

