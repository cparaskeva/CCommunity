<?php

/*
 * LinkedIn OAuth Connection 
 * Contains the basic methods in order to retrieve data from LinkedIn using API calls
 */

final class LinkedIn {

    private static $oauth;
    private static $base_url = "http://api.linkedin.com/v1/";
    private static $method = OAUTH_HTTP_METHOD_GET;

//Return an OAuth Connection
    private static function getOAuth() {
        //Initialize OAuth Connection
        if (self::$oauth === null) {
            self::$oauth = new OAuth("77ew1dvsq07zv3", "TuGZsKRmRCAxvqpM");
            //Set LinkedIn Application and User Secret
            self::$oauth->setToken("e7c9e022-4773-46d7-ad72-3c2033199cc9", "ebdb08a4-1b65-4022-a02c-ecd777537473");
        }

        return self::$oauth;
    }

    //Retrieves a limited number of linked companies based on the user's keyword
    public static function getLinkedInCompanies($keyword) {

        //Convert keyword to URL format
        $fkeyword = rawurlencode($keyword);
        $oauth = self::getOAuth();
        $api_url = self::$base_url . "company-search:(companies:(id,name,website-url))?keywords={$fkeyword}&count=10";
        $oauth->fetch($api_url, null, self::$method, array('x-li-format' => 'json'));
        return $oauth->getLastResponse();
    }

    /**
     * Retrieve all available ifnormation of a LinkedIn Company based on the Company ID
     * @param int companyID The identification number of the company as used in LinkedIn 
     * @return string LinkedIn Company Pofile
     * @link http://developer.linkedin.com/documents/company-lookup-api-and-fields LinkedIn Company API 
     */
    public static function getLinkedInCompanyInfo($companyID) {
        $oauth = self::getOAuth();
        $api_url = "http://api.linkedin.com/v1/companies/$companyID:(id,name,description,specialties,website-url,employee-count-range,company-type)";
        //Check if is valid company id
        // if (!is_int($companyID))
        //    return -1;
        $oauth->fetch($api_url, null, self::$method, array('x-li-format' => 'json'));
        return $oauth->getLastResponse();
    }

}
