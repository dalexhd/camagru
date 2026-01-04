all:
	docker-compose -f ./docker-compose.yml up --build

migrate:
	docker-compose -f ./docker-compose.yml exec server php cli/migrate.php migrate

migrate-rollback:
	docker-compose -f ./docker-compose.yml exec server php cli/migrate.php rollback

re: fclean all

fclean:
	docker-compose -f ./docker-compose.yml down --rmi all -v --remove-orphans