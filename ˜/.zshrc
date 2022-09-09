# Alias
  # Utilitaire
    alias zshconfig="code . ~/.zshrc"
    alias zshrefresh="source ~/.zshrc"
# Docker
    alias d-up="docker-compose up -d"
    alias d-down="docker-compose down"
    alias d-ps="docker ps"
    alias d-kill="docker-compose down --remove-orphans"
    alias d-build="docker-compose build --pull --no-cache"
    alias d-volume="docker volume ls"
    alias d-volume-kill="docker volume prune"
# Symfony
    alias su="brew upgrade symfony-cli/tap/symfony-cli"
    alias sc="symfony console"
    alias ss="symfony serve -d && symfony open:local"
    alias sk="symfony server:stop"
    alias sr="symfony server:stop && symfony serve -d"
    alias svar="symfony var:export --multiline"
    alias mailer="symfony open:local:webmail"
    alias sendmail="php bin/console messenger:consume async -vvv"