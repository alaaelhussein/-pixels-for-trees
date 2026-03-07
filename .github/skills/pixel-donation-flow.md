# Skill: Pixel Donation Flow

## Purpose
Protect the core business flow where pixel selection becomes visible impact only after a confirmed donation.

## Core Principles
1. **Selection is not ownership**: A local selection is only pending until confirmed.
2. **Paid pixels are locked**: Already funded pixels must not be selectable again.
3. **Confirmation matters**: Verified donation confirmation or webhook data should finalize ownership.
4. **The wall is proof**: The wall must clearly show what is free and what is funded.
5. **Admin visibility**: Donation events and webhook activity should be easy to inspect.
6. **Simple flow first**: Keep the donation flow understandable before adding extra features.

## Execution Steps
When using this skill:
1. Identify where the flow starts: landing, grid, recap, or admin.
2. Check how pending selections are stored and how funded pixels are represented.
3. Keep the state changes explicit.
4. Prevent duplicate ownership paths.
5. Surface the impact clearly in the UI after confirmation.