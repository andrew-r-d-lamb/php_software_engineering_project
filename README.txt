Below are the notes on the specification for the project.  The specified 
features were implemented using PHP and following the Pear standard.

1 Project Speciﬁcation
For this project, each student is asked to implement their own Metasearch En-
gine. Each project must be completed and submitted individually.
A metasearch engine is a search tool that sends user requests to several
other search engines and aggregates 1 the results into a single list or displays
them according to their source. Metasearch engines enable users to enter search
criteria once (ie using a query) and access several search engines simultaneously.
They operate on the premises that the World-Wide Web is too large for any one
search engine to index it all and that more comprehensive search results can be
obtained by combining the results from several search engines. This also may
save the user from having to use multiple search engines separately.
Basic Requirements The following are the basic requirements for the project.
Projects achieving higher marks will also have implemented some or all of the

Additional Features outlined below.
Implement a basic working version of a metasearch engine, which aggregates
search results from three different search engines (details of search engines that
you can use for your project will be given during the lectures that relate to the
project). Your engine should also contain the following basic functionality:
1. Complex Search Ability: Your engine should support both boolean AND,
OR and NOT search along with a standard basic keyword search ability. Re-
member, in order for your metasearch engine to support different search
types, the underlying search engines must ﬁrst support each type. This may
require you to send different queries to the various underlying search en-
gines, depending on how they deal with AND, OR and NOT searches. If the
underlying search engines do not support one or more of these operators,
you should document this in your report (see below).

2. Web-based User Interface: Your project should run as a Website through
which users can submit queries and see results. You should aim for a user
interface that makes it easy for users to perform searches, navigate through
the results and conﬁgure any options that they may have with regard to the
processing of results.

3. Results Display: Your engine should include a display module which sup-
ports two ways of displaying the search results. The user should be allowed to
choose which display option to select each time they search. The two display
options to implement are:
(a) Non-aggregrated: You should display the results as a series of separate
search result lists, organised by search engine. For example, display the
results from search engine 1, followed by the results from search engine
2, and so on.
(b) Aggregrated: You must also implement an aggregation technique of
your choice. You are asked to research and review well-known aggregation
methods before choosing a suitable one to implement for your project.
The output of the aggregation process should be displayed as a single
list of combined results.

4. Evaluation: For this section, you are asked to report on the performance of
your engine under different settings using a variety of Information Retrieval
evaluation metrics. You are asked to use the static collection of 50 queries
that are available on the module Moodle page for all experimental runs
and report averages over this query set. To evaluate a search engine’s results,
a gold standard is required for comparison. This will be discussed in more
detail during the lecture sessions scheduled for this module.
Because many search APIs limit the number of results you can obtain for
each query to 100, your experiments should evaluate the top 100 generated
results against the top 100 results for each query from the gold standard.
In all of your experiments you should report the resulting MAP, Precision @
10, Precision, Recall and F-measure scores. In particular, your experimental
analysis should:
(a) Compare the performance of the two chosen ways to display the results
(aggregated v non-aggregated) across the 50 queries. Which performs
better? Give some insight as to why you think this is so. (Note that
evaluating non-aggregated results requires you to evaluate the three un-
derlying result lists separately.)
(b) Perform some statistical analysis to demonstrate whether or not the
difference in evaluation scores is signiﬁcant.

Additional Features In order to achieve a higher mark for this project, you
should also implement some or all of the following features:
1. Extend the display module to support the clustering of results where each
cluster represents a facet of the original query using at least one clustering
technique of your choice. For example, in response to an ambiguous query
such as Jaguar, one cluster might contain results related to Jaguar (the car)
and another might contain results related to Jaguar (the cat).
2. Add query re-write functionality (where a search query is modiﬁed or ex-
panded in order to improve the quality of the results relative to the user’s
information need). Include evaluation results and describe whether or not
this added functionality improves search performance in your ﬁnal report.
3. Run a user evaluation (using approximately 20 subjects) to evaluate user
satisfaction with the overall metasearch system. There are two parts to a
search engine’s user experience: the user interface (ie design of the search
forms and results pages) and the functionality (ie how well it ﬁnds and sorts
relevant results). You should devise an evaluation form that assesses both
aspects. In particular, this user evaluation should assess their satisfaction
with respect to each display option:
(a) which display form (aggregated or non-aggregated) users prefer.
(b) which display form (clustered or non-clustered) users prefer.
