<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Host;
use App\Models\User;
use App\Models\DhcpEntry;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cant_see_the_home_page()
    {
        $this->get(route('home'))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_the_home_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('home'))->assertOk();
    }

    /** @test */
    public function the_home_page_shows_the_most_recent_dhcp_entries_by_default()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $dhcp1 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(2)]);
        $dhcp2 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(1)]);
        $dhcp3 = DhcpEntry::factory()->create(['updated_at' => now()]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('DHCP Entries');
        $response->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address]);
        $response->assertSeeLivewire('dhcp-entries');
    }

    /** @test */
    public function users_can_filter_the_list_of_dhcp_entries_in_various_ways()
    {
        $user = User::factory()->create();
        $dhcp1 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(2), 'hostname' => 'dhcp1', 'ip_address' => '192.168.12.2', 'mac_address' => '00:11:22:33:44:55', 'owner' => 'jenny@example.com', 'added_by' => 'bert@example.com']);
        $dhcp2 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(1), 'hostname' => 'dhcp2', 'ip_address' => '192.168.12.3', 'mac_address' => '00:11:22:33:44:56', 'owner' => 'jimmy@example.com', 'added_by' => 'bert@example.com']);
        $dhcp3 = DhcpEntry::factory()->create(['updated_at' => now(), 'hostname' => 'dhcp3', 'ip_address' => '192.168.12.4', 'mac_address' => '00:11:22:33:44:57', 'owner' => 'poppy@example.com', 'added_by' => 'sarah@example.com']);

        Livewire::actingAs($user)->test('dhcp-entries')
            ->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address])
            ->set('search', 'dhcp1')
            ->assertSee($dhcp1->mac_address)
            ->assertDontSee($dhcp2->mac_address)
            ->assertDontSee($dhcp3->mac_address)
            ->set('search', 'dhcp2')
            ->assertSee($dhcp2->mac_address)
            ->assertDontSee($dhcp1->mac_address)
            ->assertDontSee($dhcp3->mac_address)
            ->set('search', 'd')
            ->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address])
            ->set('search', '      ')
            ->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address])
            ->set('search', '')
            ->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address]);
    }

    /** @test */
    public function users_can_sort_the_entries_by_field()
    {
        $user = User::factory()->create();
        $dhcp1 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(2), 'hostname' => 'dhcp1', 'ip_address' => '192.168.12.2', 'mac_address' => '00:11:22:33:44:55', 'owner' => 'aaaaa', 'added_by' => 'aaaaa']);
        $dhcp2 = DhcpEntry::factory()->create(['updated_at' => now()->subDays(1), 'hostname' => 'dhcp2', 'ip_address' => '192.168.12.3', 'mac_address' => '00:11:22:33:44:56', 'owner' => 'bbbbbb', 'added_by' => 'bbbbbb']);
        $dhcp3 = DhcpEntry::factory()->create(['updated_at' => now(), 'hostname' => 'dhcp3', 'ip_address' => '192.168.12.4', 'mac_address' => '00:11:22:33:44:57', 'owner' => 'cccccc', 'added_by' => 'cccccc']);

        Livewire::actingAs($user)->test('dhcp-entries')
            ->assertSeeInOrder([$dhcp3->mac_address, $dhcp2->mac_address, $dhcp1->mac_address])
            ->call('sortBy', 'mac_address')
            ->assertSeeInOrder([$dhcp1->mac_address, $dhcp2->mac_address, $dhcp3->mac_address])
            ->call('sortBy', 'ip_address')
            ->assertSeeInOrder([$dhcp1->mac_address, $dhcp2->mac_address, $dhcp3->mac_address])
            ->call('sortBy', 'hostname')
            ->assertSeeInOrder([$dhcp1->mac_address, $dhcp2->mac_address, $dhcp3->mac_address])
            ->call('sortBy', 'owner')
            ->assertSeeInOrder([$dhcp1->mac_address, $dhcp2->mac_address, $dhcp3->mac_address])
            ->call('sortBy', 'added_by')
            ->assertSeeInOrder([$dhcp1->mac_address, $dhcp2->mac_address, $dhcp3->mac_address]);
    }
}
