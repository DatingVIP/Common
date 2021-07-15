# customization

PACKAGE_NAME = icanboogie/common
PHPUNIT_VERSION = phpunit-4.8.phar
PHPUNIT_FILENAME = build/$(PHPUNIT_VERSION)
PHPUNIT = php $(PHPUNIT_FILENAME)

# do not edit the following lines

.PHONY: all
all: $(PHPUNIT_FILENAME) vendor

.PHONY: usage
usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

vendor:
	@composer install

.PHONY: update
update:
	@composer update

$(PHPUNIT_FILENAME):
	mkdir -p build
	wget https://phar.phpunit.de/$(PHPUNIT_VERSION) -O $(PHPUNIT_FILENAME)

.PHONY: test
test: all
	@$(PHPUNIT)

.PHONY: test-coverage
test-coverage: all
	@mkdir -p build/coverage
	@$(PHPUNIT) --coverage-html build/coverage

.PHONY: test-coveralls
test-coveralls: all
	@mkdir -p build/logs
	@$(PHPUNIT) --coverage-clover build/logs/clover.xml

.PHONY: doc
doc: vendor
	@mkdir -p build/docs
	@apigen generate \
	--source lib \
	--destination build/docs/ \
	--title "$(PACKAGE_NAME)" \
	--template-theme "bootstrap"

.PHONY: clean
clean:
	@rm -fR build
	@rm -fR vendor
	@rm -f composer.lock
