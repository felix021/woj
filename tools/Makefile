CC=$(CXX)
CXXFLAGS=-I./../ \
				 -I./ \
				 -I/usr/include/libxml2 \
				 -L/usr/lib/libxml2  \
				 -I/usr/include/mysql \
				 -L/usr/lib/mysql \
				 -Wall -Werror -Wno-sign-compare -ansi -O3 -MD -D_THIS_IS_SERVER_LOG_
EXEFLAGS=-lxml2 \
				 -lpthread \
				 -lmysqlclient

SOURCES:=$(filter-out $(wildcard rejudge_all.cc),$(wildcard *.cc))
REJUDGE_SOURCES:=$(wildcard rejudge_all.cc)
DEPS_SOURCES:=$(filter-out $(wildcard ./../base/test_*.cc),$(wildcard ./../base/*.cc))
OBJECT_SOURCES:=$(wildcard ./object/*.cc)
DATA_SOURCES:=$(wildcard ./data/*.cc)

# All objects will auto build by "$(CXX) $(CXXFLAGS) -c" if there is no rule
# to make it
OBJS:=$(patsubst %.cc,%.o,$(SOURCES))
REJUDGE:=$(patsubst %.cc,%.o,$(REJUDGE_SOURCES))
DEPS:=$(patsubst %.cc,%.o,$(DEPS_SOURCES))
OBJECT:=$(patsubst %.cc,%.o,$(OBJECT_SOURCES))
DATA:=$(patsubst %.cc,%.o,$(DATA_SOURCES))

.PHONY: all clean rebuild 

all: main

clean:
	@rm -f $(OBJS) $(REJUDGE) *.d main rejudge

rebuild: clean all

main: $(OBJS) $(DEPS) $(OBJECT) $(DATA)
	$(CXX) $(CXXFLAGS) -o $@ $^ $(EXEFLAGS)

rejudge: $(REJUDGE) $(DEPS)
	$(CXX) $(CXXFLAGS) -o $@ $^
