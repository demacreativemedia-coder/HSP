<?php
namespace Tests\Feature;
use Tests\TestCase;
class PublicRoutesV8Test extends TestCase {
 public function test_program_list_is_public():void { $this->get('/program')->assertStatus(200); }
 public function test_article_list_is_public():void { $this->get('/artikel')->assertStatus(200); }
}
