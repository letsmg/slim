<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Testes de segurança e conformidade ISO 27001
 * Cobre: sanitização de entradas, hash de senhas (Argon2id)
 */
class SecurityTest extends TestCase
{
    public function testSanitizeInputsHelper()
    {
        $this->assertTrue(function_exists('sanitize_inputs'));

        $dirty = [
            "name" => "  <script>alert('xss')</script>João  ",
            "email" => "  TESTE@EXEMPLO.COM  ",
        ];

        $clean = sanitize_inputs($dirty);

        // Verifica trim
        $this->assertStringStartsNotWith(" ", $clean["name"]);
        $this->assertStringEndsNotWith(" ", $clean["name"]);

        // Verifica strip_tags
        $this->assertStringNotContainsString("<script>", $clean["name"]);
    }

    public function testPasswordHashArgon2id()
    {
        $password = "Str0ng!Pass";
        $hash = password_hash($password, PASSWORD_ARGON2ID);

        $this->assertStringContainsString('argon2id', $hash);
        $this->assertTrue(password_verify($password, $hash));
    }
}
