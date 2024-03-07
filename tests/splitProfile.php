<?php

namespace Test\SplitProfile;
use \Exception;
use StarkBank\SplitProfile;
use \DateTime;


class TestSplitProfile
{
    public function putSplitProfile()
    {
        $profile = self::examples();
        $profile = SplitProfile::put($profile);
        if (is_null($profile)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $splitProfiles = iterator_to_array(SplitProfile::query(["limit" => 10, "before" => new DateTime("now")]));
        
        if (count($splitProfiles) != 1) {
            throw new Exception("failed");
        }

        $splitProfile = SplitProfile::get($splitProfiles[0]->id);
        if ($splitProfiles[0]->id != $splitProfile->id) {
            throw new Exception("failed");
        }
    }


    public static function examples()
    {
        return [    
            new SplitProfile([
                "interval"=> "day",
                "delay"=> 0,
            ]),
        ];
    }
}

echo "\n\nSplitProfile:";

$test = new TestSplitProfile();

echo "\n\t- create split receiver\n";
$test->putSplitProfile();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
