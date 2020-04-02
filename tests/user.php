<?php

namespace Test;
use StarkBank\Project;

class TestUser
{
    public static function project()
    {
        return new Project(
            "sandbox",
            "9999999999999999",
            "
            -----BEGIN EC PRIVATE KEY-----
            MHQCAQEEIBEcEJZLk/DyuXVsEjz0w4vrE7plPXhQxODvcG1Jc0WToAcGBSuBBAAK
            oUQDQgAE6t4OGx1XYktOzH/7HV6FBukxq0Xs2As6oeN6re1Ttso2fwrh5BJXDq75
            mSYHeclthCRgU8zl6H1lFQ4BKZ5RCQ==
            -----END EC PRIVATE KEY-----
            "
        );
    }
}
