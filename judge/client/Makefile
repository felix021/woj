CC=$(CXX)
CXXFLAGS=-I../../ \
				 -I../kernel_module -L../kernel_module \
	       -Wall -Werror -Wno-sign-compare -ansi -O3 -MD
VPATH=../kernel_module ../../base

ALL_SOURCES:=$(wildcard *.cc)
TEST_SOURCES:=$(wildcard test_*.cc)
SOURCES=$(filter-out $(TEST_SOURCES),$(ALL_SOURCES))
DEPS_SOURCES:=$(wildcard ../../base/*.cc)

# All objects will auto build by "$(CXX) $(CXXFLAGS) -c" if there is no rule
# to make it
ALL_OBJS:=$(patsubst %.cc,%.o,$(ALL_SOURCES))
OBJS:=$(patsubst %.cc,%.o,$(SOURCES))
TESTS:=$(patsubst %.cc,%,$(TEST_SOURCES))
DEPS:=$(patsubst %.cc,%.o,$(DEPS_SOURCES))

.PHONY: all clean rebuild test

all: judge_client

clean:
	@rm -f $(ALL_OBJS) judge_client $(TESTS) *.d

rebuild: clean all

test : $(TESTS)

judge_client : main.cc $(OBJS) $(DEPS) -lkmmon
	$(CXX) $(CXXFLAGS) $(EXEFLAGS) -o $@ $^ $(DEPS)

test_compile : test_compile.cc compile.o trace.o utils.o $(DEPS) -lkmmon
	$(CXX) $(CXXFLAGS) -o $@ $^

test_run : test_run.cc run.o trace.o utils.o $(DEPS) -lkmmon
	$(CXX) $(CXXFLAGS) -o $@ $^

test_judge: test_judge.cc judge.o trace.o utils.o $(DEPS) -lkmmon
	$(CXX) $(CXXFLAGS) -o $@ $^
