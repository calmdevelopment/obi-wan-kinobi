#!/bin/bash
./artisan clear-compiled && ./artisan ide-helper:generate && ./artisan ide-helper:models --write && ./artisan ide-helper:meta
vendor/bin/php-cs-fixer fix
