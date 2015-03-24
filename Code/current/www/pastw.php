<!DOCTYPE html>
<html>
    <?php
    $atoken = "eLYVAxnZrreZwLDdjCeujENUdYGqKyGY";
    $header = array(
        'Content-length: 0',
        'Content-type: application/json',
        'Token: ' . $atoken
        );
    $urlbase = "http://www.ncdc.noaa.gov/cdo-web/api/v2/";

    //$endpoint = "data?datasetid=NORMAL_DLY&datacategoryid=TEMP&locationid=ZIP:62025&startdate=2010-02-12&enddate=2010-02-12&limit=50&offset=1";

    //$endpoint = "data?datasetid=NORMAL_DLY&datatypeid=DLY-TAVG-NORMAL&locationid=ZIP:62025&startdate=2010-02-12&enddate=2010-02-22&limit=20";
    //$endpoint = "locations?locationcategoryid=ZIP&datasetid=GHCND&limit=100&offset=19500";
    //$endpoint = "locations?datasetid=NORMAL_DLY&locationcategoryid=ZIP&locationid=62258";
    //$endpoint = "datacategories?datasetid=GHCND";
    $endpoint = "data?datasetid=GHCND&locationid=ZIP:62025&startdate=2015-02-12&enddate=2015-02-12&limit=50&offset=1";

    $fullURL = $urlbase.$endpoint;

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $fullURL);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

    $curl_response = curl_exec($curl);
    curl_close($curl);

    echo '<pre>';
    print_r($curl_response);
    echo '</pre>';
    ?>
</html>