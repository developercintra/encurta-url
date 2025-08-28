<?php

it('redirects guests to login', function () {
    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertRedirect('/login');
});
