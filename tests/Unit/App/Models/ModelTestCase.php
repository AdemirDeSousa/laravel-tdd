<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;

    abstract protected function expectedTraits(): array;

    abstract protected function expectedFillables(): array;

    abstract protected function expectedCasts(): array;

    public function test_traits()
    {
        $modelTraits = array_keys(class_uses($this->model()));

        $this->assertEquals($this->expectedTraits(), $modelTraits);
    }

    public function test_fillable()
    {
        $modelFillable = $this->model()->getFillable();

        $this->assertEquals($this->expectedFillables(), $modelFillable);
    }

//    public function test_incrementing_is_false()
//    {
//        $this->assertFalse($this->model()->incrementing);
//    }

    public function test_has_casts()
    {
        $modelCasts = $this->model()->getCasts();

        $this->assertEquals($this->expectedCasts(), $modelCasts);
    }
}
