<?
/*
* Copyright (c) 2010 Josh Grochowski (josh[at]kastang[dot]com)
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/

/**
 * This Class provides an API to access the WoW Realm Status page. 
 *
 * For details of how/what information is returned, please read the function
 * header in the code below. 
 *
 * Functions:
 * --getAllServers() - Returns an associative array of all Server Names, 
 * Statuses, Populations, Types, and Locale. 
 *
 * --getServerType($) - Returns the type of a given server. 
 * --getServerStatus($) - Returns the status of a given server. 
 * --getServerPopulation($) - Returns the population of a given server. 
 * --getServerLocale($) - Returns the locale of a given server. 
 *
 * @author Josh Grochowski (josh[at]kastang[dot]com)
 */

class RealmStatus {

    /**
     * The WoW Realm Status URL for US Servers. This script should also work
     * for European servers, but I have not tested the functionality. 
     */
    private $STATUS_URL = "http://us.battle.net/wow/en/status";

    /**
     * When getAllServers() is called, the resulting associative array will
     * be stored in this variable. All other functions will use the information
     * stored in this array to keep calls from the WoW Armory at a minimum. 
     */
    private $SERVER_ARRAY = null;

    /**
     * This function is initially called when the RealmStatus object is created. 
     * It populates the $SERVER_ARRAY.
     */
    function __construct() {
        $this->getAllServers();
    }

    /**
     * This function returns an associative array containing information 
     * about every WoW Server. 
     *
     * The associative array is in the following format:
     * "name" - Name of the server. 
     * "status" - Status of the server ("up" or "down")
     * "type" - Type of server (PvE, PvP, RP)
     * "population" - Low, Medium, High server status. 
     * "locale" - Location of WoW Server (United States by default)
     */
    function getAllServers() {

        if($this->SERVER_ARRAY == null) {

            $dom = new domDocument;
            $dom->loadHTML(file_get_contents($this->STATUS_URL));
            $dom->preserveWhiteSpace = false;

            $xpath = new DOMXPath($dom);

            $name = $xpath->query('//td[@class="name"]');
            $status = $xpath->query('//td[@class="status"]/div/@class');
            $type = $xpath->query('//td[@class="type"]/span');
            $population = $xpath->query('//td[@class="population"]/span');
            $locale = $xpath->query('//td[@class="locale"]');

            /* Generate the number of servers */
            $ctr = 0;
            foreach($name as $n) {
                $ctr++;
            }

            $serverArray = array();

            for($i=0;$i<$ctr;$i++) {

                $s = explode(" ", trim($status->item($i)->nodeValue));

                $info = array (
                    "name" => trim($name->item($i)->nodeValue),
                    "status" => $s[1],
                    "type" => substr(substr(trim($type->item($i)->nodeValue),0,-1),1),
                    "population" => trim($population->item($i)->nodeValue),
                    "locale" => trim($locale->item($i)->nodeValue)
                );

                array_push($serverArray, $info);
            }

            $this->SERVER_ARRAY = $serverArray;
        }

        return $this->SERVER_ARRAY;
    }

    /**
     * Given a Server Name, this function will return the
     * type of server. 
     *
     * @return "PvP", "PvE", "RP", or "RP-PvP" (null will be
     * returned if the given server name doesn't exist.
     */
    function getServerType($serverName) {

        foreach($this->SERVER_ARRAY as $s) {
            if($s["name"] == $serverName) {
                return $s["type"];
            }
        }

        return null;
    }

    /**
     * Given a Server Name, this function will return the
     * status of server. 
     *
     * @return up or down. (null will be returned if the given 
     * server name doesn't exist.)
     */
    function getServerStatus($serverName) {

        foreach($this->SERVER_ARRAY as $s) {
            if($s["name"] == $serverName) {
                return $s["status"];
            }
        }

        return null;
    }



    /**
     * Given a Server Name, this function will return the 
     * population of the server. 
     *
     * @return low, medium, high (null will be returned if the
     * given server name doesn't exist.)
     */
    function getServerPopulation($serverName) {
        foreach($this->SERVER_ARRAY as $s) {
            if($s["name"] == $serverName) {
                return $s["population"];
            }
        }

    }

    /**
     * Given a Server Name, this function will return the
     * locale of the server. 
     *
     * @return 'United States' (Assuming using US Servers). (Null
     * will be returned if the given server name doesn't exist.)
     */
    function getServerLocale($serverName) {
        foreach($this->SERVER_ARRAY as $s) {
            if($s["name"] == $serverName) {
                return $s["locale"];
            }
        }

    }
}
