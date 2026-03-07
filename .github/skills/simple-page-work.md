# Skill: Simple Page Work

## Purpose
Edit PHP pages and browser scripts in a way that keeps the site direct, clear, and easy to demo.

## Core Principles
1. **One page, one job**: Each page should have a very obvious purpose.
2. **Keep CTAs direct**: Buttons and labels should clearly tell the user what happens next.
3. **Preserve mixed structure**: Shared layout belongs in `includes/`; page logic belongs in the page or its small script.
4. **Do not over-style**: Favor clarity over flashy UI.
5. **User story first**: The donation-to-impact story should be visible immediately.
6. **Prefer French continuity**: Match the existing language used in the current page unless asked otherwise.

## Execution Steps
When using this skill:
1. Identify the page goal before editing it.
2. Keep the change small and visible.
3. If JavaScript is needed, put page-specific behavior in the matching script file.
4. If shared markup repeats, move only the repeated part into `includes/`.
5. Re-read the page to make sure a user can understand it quickly.