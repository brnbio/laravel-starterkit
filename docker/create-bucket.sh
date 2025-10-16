#!/usr/bin/env bash

mc alias set local ${AWS_ENDPOINT} ${AWS_ACCESS_KEY_ID} ${AWS_SECRET_ACCESS_KEY}
mc mb local/${AWS_BUCKET} --ignore-existing
mc anonymous set public local/${AWS_BUCKET}

exit 0