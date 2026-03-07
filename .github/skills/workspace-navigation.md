# Skill: Workspace Navigation

## Purpose
Work in the correct folder and avoid introducing tools that do not belong in this repo.

## Core Principles
1. **Main app location**: The actual website code lives in `Pixels/`.
2. **Simple runtime**: This project is served with PHP, not Node.
3. **No npm workflow**: Do not add or run npm-based tooling for normal project work.
4. **Use real file paths**: Edit files where the app actually lives instead of assuming a framework layout.
5. **Preserve structure**: Use the existing `includes/` and `assets/js/` structure.

## Execution Steps
When using this skill:
1. Confirm whether the task affects files in `Pixels/`, `Pixels/includes/`, or `Pixels/assets/js/`.
2. Make changes inside the current PHP site structure.
3. If a local server command is needed, prefer the basic PHP server for this project.
4. Avoid suggesting build steps or package install steps unless the user explicitly asks for them.
5. Keep the repo lightweight.