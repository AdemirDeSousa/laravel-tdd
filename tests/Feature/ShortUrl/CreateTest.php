<?php

namespace Tests\Feature\ShortUrl;

use App\Facades\Actions\CodeGenerator;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_short_url()
    {
//        $this->withoutExceptionHandling();

        $randomCode = Str::random(5);

        CodeGenerator::shouldReceive('run')->once()->andReturn($randomCode);

        $this->postJson(route('api.short-url.store'), [
            'url' => 'https://www.youtube.com/watch?v=TGbIVuipAiU&ab_channel=PinguimdoLaravel%C2%B7RafaelLunardelli'
        ])->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'short_url' => config('app.url') . '/' . $randomCode
        ]);

        $this->assertDatabaseHas('short_urls', [
            'url' => 'https://www.youtube.com/watch?v=TGbIVuipAiU&ab_channel=PinguimdoLaravel%C2%B7RafaelLunardelli',
            'short_url' => config('app.url') . '/' . $randomCode,
            'code' => $randomCode
        ]);
    }

    /** @test */
    public function url_should_be_valid_url()
    {
        $this->postJson(route('api.short-url.store'), [
            'url' => 'not-valid-url'
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'url' => __('validation.url', ['attribute' => 'url'])
            ]);
    }

    /** @test */
    public function it_should_return_the_existed_code_if_the_url_is_the_same()
    {
        ShortUrl::factory()->create([
            'url' => 'https://www.youtube.com/watch?v=TGbIVuipAiU&ab_channel=PinguimdoLaravel%C2%B7RafaelLunardelli',
            'short_url' => config('app.url') . '/1234',
            'code' => '1234'
        ]);

        $this->postJson(route('api.short-url.store'), [
            'url' => 'https://www.youtube.com/watch?v=TGbIVuipAiU&ab_channel=PinguimdoLaravel%C2%B7RafaelLunardelli'
        ])->assertJson([
            'short_url' => config('app.url') . '/1234'
        ]);

        $this->assertDatabaseCount('short_urls', 1);
    }
}
