<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_home_returns_200(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_privacy_returns_200(): void
    {
        $this->get('/privacy')->assertOk();
    }

    public function test_unknown_page_returns_404(): void
    {
        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_home_shows_required_headings(): void
    {
        $response = $this->get('/');

        $response->assertSee('社長、', false);
        $response->assertSee('そのシステム', false);
        $response->assertSee('AIで会社の業務システムを1週間で開発・公開。', false);
        $response->assertSee('BuildOneとは', false);
        $response->assertSee('開発システム', false);
        $response->assertSee('開発の流れ', false);
    }

    public function test_home_shows_all_system_names(): void
    {
        $response = $this->get('/');

        foreach (['BuildOne Entry', 'BuildOne Time', 'BuildOne Shift', 'BuildOne Reserve', 'BuildOne Report'] as $name) {
            $response->assertSee($name, false);
        }
        $response->assertSee('第1弾・開発予定', false);
        $response->assertSee('Coming Soon', false);
    }

    public function test_contact_shows_placeholder_without_invalid_link_when_unset(): void
    {
        config(['buildone.contact.url' => null, 'buildone.contact.email' => null]);

        $response = $this->get('/');

        $response->assertSee('お問い合わせ窓口準備中', false);
        $response->assertDontSee('mailto:', false);
    }

    public function test_contact_button_appears_when_email_is_set(): void
    {
        config(['buildone.contact.url' => null, 'buildone.contact.email' => 'info@example.com']);

        $response = $this->get('/');

        $response->assertSee('mailto:info@example.com', false);
        $response->assertDontSee('お問い合わせ窓口準備中', false);
    }

    public function test_contact_url_takes_precedence(): void
    {
        config(['buildone.contact.url' => 'https://example.com/contact', 'buildone.contact.email' => 'info@example.com']);

        $response = $this->get('/');

        $response->assertSee('https://example.com/contact', false);
        $response->assertDontSee('mailto:', false);
    }

    public function test_youtube_placeholder_when_video_unset(): void
    {
        config(['buildone.youtube.video_url' => null]);

        $response = $this->get('/');

        $response->assertSee('動画は近日公開', false);
        $response->assertDontSee('<iframe', false);
    }

    public function test_mobile_menu_structure_exists(): void
    {
        $response = $this->get('/');

        $response->assertSee('id="menu-toggle"', false);
        $response->assertSee('id="mobile-menu"', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('aria-controls="mobile-menu"', false);
    }

    public function test_seo_tags_present(): void
    {
        $response = $this->get('/');

        $response->assertSee('<meta name="description"', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('lang="ja"', false);
    }

    public function test_debug_info_is_not_shown_when_debug_disabled(): void
    {
        config(['app.debug' => false]);

        $response = $this->get('/no-such-page');

        $response->assertNotFound();
        $response->assertSee('ページが見つかりません', false);
        $response->assertDontSee('Stack trace', false);
    }
}
