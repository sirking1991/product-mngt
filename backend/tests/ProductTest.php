<?php

use Illuminate\Support\Facades\DB;

class ProductTest extends TestCase
{

    var $jsonStructure = ['id', 'code', 'name', 'url', 'created_at', 'updated_at'];

    function __construct()
    {
        parent::__construct();
        DB::table('products')->truncate();
    }

    // normal create, should return 200 with json
    public function testCase101()
    {
        $parameters = ['code' => '12345', 'name' => 'One two three four five', 'url' => 'http://12345.com'];
        $this->post("products", $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure( $this->jsonStructure );
    }

    // create product with existing code. should return 409
    public function testCase102()
    {
        // duplicate code, should return 409 - conflict
        $parameters = ['code' => '12345', 'name' => 'One two three four five', 'url' => 'http://12345.com' ];
        $this->post("products", $parameters);
        $this->seeStatusCode(409);
    }

    // create product with existing url. should return 409
    public function testCase103()
    {
        // duplicate url, should produce 409 - conflict
        $parameters = ['code' => '23456', 'name' => 'Two three four five six', 'url' => 'http://12345.com'];
        $this->post("products", $parameters);
        $this->seeStatusCode(409);
    }

    // create another product, should return 200 with json
    public function testCase104()
    {
        // existing name, should produce 200
        $parameters = ['code' => '23456', 'name' => 'Two three four five six', 'url' => 'http://23456.com'];
        $this->post("products", $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure( $this->jsonStructure );
    }

    // create another product with an existing name, should return 200 with json
    public function testCase105()
    {
        // existing name, should produce 200
        $parameters = ['code' => '34567', 'name' => 'Two three four five six', 'url' => 'http://34567.com'];
        $this->post("products", $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure( $this->jsonStructure );
    }

    // Update existing product, should return 200
    public function testCase201()
    {
        $parameters = ['code' => '12345', 'name' => 'Uno dos tress quatro cincu', 'url'  => 'http://12345.com'];
        $this->put("products/1", $parameters);
        $this->seeStatusCode(200);
        $this->seeJsonStructure( $this->jsonStructure );
    }

    // Update a non existing product, should return 404
    public function testCase202()
    {
        $parameters = ['code' => 'TEST', 'name' => 'Testing', 'url'  => 'http://test.com'];
        $this->put("products/100", $parameters);
        $this->seeStatusCode(404);
    }

    // // Update product code with existing product code, should return 409
    // public function testCase203()
    // {
    //     $parameters = ['code' => '12345', 'name' => 'test', 'url'  => 'http://test.com'];
    //     $this->put("products/2", $parameters);
    //     $this->seeStatusCode(409);
    // }

    // // Update product code with existing product url, should return 409
    // public function testCase204()
    // {
    //     $parameters = ['code' => 'test', 'name' => 'Uno dos tress quatro cincu', 'url'  => 'http://12345.com'];
    //     $this->put("products/2", $parameters);
    //     $this->seeStatusCode(409);
    // }

    // Retrieve all product, should return 200 with array of products
    public function testCase301()
    {
        $this->get("products");
        $this->seeStatusCode(200);
        $this->seeJsonStructure([ $this->jsonStructure ]);
    }

    // Retrieve specific product, should return 200 with json
    public function testCase302()
    {
        $this->get("products/1");
        $this->seeStatusCode(200);
        $this->seeJsonStructure( $this->jsonStructure );
    }

    // Retrieve non existing product, should return 404
    public function testCase303()
    {
        $this->get("products/100");
        $this->seeStatusCode(404);
    }

    // Delete an existing product, should return 204
    public function testCase401()
    {
        $this->delete("products/1");
        $this->seeStatusCode(204);
    }

    // Delete an non existing product, should return 204
    public function testCase402()
    {
        $this->delete("products/100");
        $this->seeStatusCode(404);
    }

}
