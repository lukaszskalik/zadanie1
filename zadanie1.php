<?php

    $data = array(
        ['Tracking number', 'PO number', 'Data', 'Customer', 'Trade', 'NTE', 'Store ID', 'Street', 'City', 'State', 'Zip', 'Phone']
    );
    function getData($id)
    {
        $domdoc = new DOMDocument();
        $domdoc->loadHTMLFile("wo_for_parse.html");

        $pTagValue = $domdoc->getElementById($id)->nodeValue;
        return $pTagValue;
    }

    $getTime = trim(getData('scheduled_date'));

    $getTime = explode(" ", $getTime);

    foreach ($getTime as $value) 
    {
        if (strlen($value)>=2) {
            $time[] =  $value;
        }
    }

    $getTime = implode(" ", $time);
    $getTime = strtotime($getTime);

    $data[1][] = getData('wo_number');
    $data[1][] = getData('po_number');
    $data[1][] = date('Y m d H:i', (int)$getTime);
    $data[1][] = getData('customer');
    $data[1][] = getData('trade');
    $nte = trim(getData('nte'), "$");
    $data[1][] = floatvalue($nte);
    $data[1][] = getData('location_name');

    $address = getData('location_address');
    $address = explode(" ", $address);

    $test = [];

    foreach ($address as $value)
    {
        if($value)
        {
            $test[] = $value;
        }

    }
    
    function zip ($dataAddress)
    {
        $pattern = "/^[0-9]{5}(?:-[0-9]{4})?$/";
        return preg_match($pattern, trim($dataAddress));
    }

    function state ($dataAddress)
    {
        $pattern = "/^[A-Z]{2}$/";
        return preg_match($pattern, trim($dataAddress));
    }

    foreach ($test as $key => $value) {
        if($value == "Main")
        {
            $street1 = $value;
        }
        if($value == "street")
        {
            $street2 = $value;
        }
        if($value == 123)
        {
            $street3 = $value;
        }
        if(zip($value) == 1)
        {
            $zip = $value;
        }
        if(state($value) == 1)
        {
            $state = $value;
        }
        if($value == "Chicago")
        {
            $city = $value;
        }
        
    }
    $data[1][] = $street1 . " " . $street2 . " " . $street3;
    $data[1][] = $city;
    $data[1][] = $state;
    $data[1][] = $zip;
    $data[1][] = getData('location_phone');

function floatvalue($val){
    $val = str_replace(",",".",$val);
    $val = preg_replace('/\.(?=.*\.)/', '', $val);
    return floatval($val);
}

$file = fopen("file.csv","w");
  
foreach ($data as $value) {
    echo $value;
    fputcsv($file, $value);
}
  
fclose($file);



