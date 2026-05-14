<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductEnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_enquiry_is_stored_on_valid_submit(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'price' => 1000,
            'category' => 'Home',
        ]);

        $response = $this->post(route('products.enquiry.store', $product->slug), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-444-555',
            'subject' => 'Enquiry test',
            'message' => 'Need pricing and delivery timeline.',
        ]);

        $response->assertSessionHas('enquiry_success');
        $this->assertDatabaseHas('enquiries', [
            'email' => 'john@example.com',
            'product_id' => $product->id,
        ]);
    }

    public function test_product_enquiry_rejects_invalid_payload(): void
    {
        $product = Product::create([
            'title' => 'Invalid Product',
            'slug' => 'invalid-product',
            'description' => 'Invalid description',
            'price' => 1500,
            'category' => 'Kitchen',
        ]);

        $response = $this->post(route('products.enquiry.store', $product->slug), [
            'name' => '',
            'email' => 'wrong-format',
            'phone' => '',
            'message' => 'short',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'message']);
    }
}
