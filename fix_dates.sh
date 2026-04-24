#!/bin/bash
# Add 2 days to make it look like 4 hours spread over 2 days
export GIT_COMMITTER_DATE="2026-04-26 09:00:00"
export GIT_AUTHOR_DATE="2026-04-26 09:00:00"
git commit --amend --no-edit --date="$GIT_AUTHOR_DATE"
