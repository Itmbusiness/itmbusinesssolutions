# Laravel + Livewire Starter Kit

This repository is the application foundation for ITM Business Solutions.

## Introduction

Our Laravel + [Livewire](https://livewire.laravel.com) starter kit provides a robust, modern starting point for building Laravel applications with a Livewire frontend.

Livewire is a powerful way of building dynamic, reactive, frontend UIs using just PHP. It's a great fit for teams that primarily use Blade templates and are looking for a simpler alternative to JavaScript-driven SPA frameworks like React and Vue.

This Livewire starter kit utilizes Livewire 4, TypeScript, Tailwind, and the [Flux UI](https://fluxui.dev) component library.

If you are looking for the alternate configurations of this starter kit, they can be found in the following branches:

- [workos](https://github.com/laravel/livewire-starter-kit/tree/workos) - if WorkOS is selected for authentication

## Official Documentation

Documentation for all Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Local Setup

Requirements: PHP 8.3+, Composer, Node.js, and npm.

```bash
composer setup
composer run dev
```

The setup script installs dependencies, creates `.env`, generates the application key, prepares SQLite, runs migrations, installs frontend dependencies, and builds the Vite assets.

## Coding Agents

Repository-wide conventions and verification commands live in `AGENTS.md`. Claude Code reads `CLAUDE.md`, which imports the same shared instructions. Laravel Boost is included as a development dependency; after the initial Composer installation, run `php artisan boost:install` and select the Codex and Claude Code integrations.

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

All contributions to the Starter Kits from now on should be made through [Maestro](https://github.com/laravel/maestro).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + Livewire starter kit is open-sourced software licensed under the MIT license.
