<div class="rules-section" >

<p>
  In short: Teams of up to three persons try to solve as many problems as possible, without external help.
</p>

<p>
  The rules for this contest is given by the
  [ICPC regional contest rules](http://icpc.global/regionals/rules),
  with the following clarifications and additions:
</p>

<h4>Who may compete</h4>
<p>
  The teams competing consist of up to three persons.
  The competition is open to everybody, as long as they are either a citizen of the NCPC countries or related to an
  entity (company or university) in the NCPC countries.
  Every team must compete for some NCPC country.
</p>

<h4>ICPC eligibility</h4>
<p>
  Teams consisting of university students, who are [ICPC eligible](https://icpc.global/regionals/rules), are encouraged to participate in the ICPC division. These may qualify for the regional finals
  ([NWERC](https://nwerc.eu)), and further to the [ICPC World Finals](https://icpc.global).
  Basically, any student born in {{ page.year | minus: 23 }} or later, or who started their university/college studies in {{ page.year | minus: 4 }} or later is eligible to compete.
  For exceptions such as retaken years, military service and so on, please refer to the [ICPC rules](https://icpc.global/regionals/rules).
  Persons who have competed in five regional finals already, or two world finals, may not compete in the ICPC division, however there are some [exceptions for the covid years](https://icpc.global/regionals/rules).
</p>

#### How and where you may compete

- The contest is held at the participating sites.
- All members of a team have to be present at the same site.
- Your team may bring a single personal computer (with one keyboard, one mouse and one screen) to use during the competition.
- You may **NOT** use any additional electronic devices (such as mobile phones) to compete.
- You may bring printed material (such as books).
- You may **NOT** communicate with anyone other than the contest organizers and your own team members.
- You **MUST** run the screen recording tool during the entire contest; separate instructions will be sent out by email before the contest starts.

#### Recommended and allowed software

The rationale is that competitors should write their own code manually.  Basic code completion is allowed but Generative AI LLM tools local or otherwise are explicitly disallowed. Any local digital databases of pre-written code are similarly disallowed. This includes old solutions, PDFs of textbooks, copies of webpages and other such things.

You may NOT use the internet for anything other than accessing the contest page and official documentation.

Official language documentation is allowed:
* https://docs.python.org/ for Python
* https://cppreference.com/ for C++
* https://devdocs.io/c/ for C
* https://docs.oracle.com/en/java/ for Java
* https://learn.microsoft.com/en-us/dotnet/csharp/ for C#
* or equivalent for your language of choice.

You are restricted to the built-in search capabilities of the above sites when using them.

Physical material has no restrictions: team reference documents, printed code, textbooks, and other such material is allowed. There is no limit on the amount of such material.

Here are some allowed/recommended editors, IDEs , debuggers and environments. If your software is not listed here, or broadly equivalent to one listed here, it is not allowed.

##### Editors

* vim / gvim / nvim
* nano / emacs
* gedit / geany / kate
* ... or similar
* **with no GenAI or LLM plugins**

##### IDEs

* Visual Studio Code, and the following plugins:
    * C/C++ by Microsoft
    * Deubgger for Java by Microsoft
    * Python by Microsoft
    * ... or similar
* Code::Blocks
* PyCharm
* ... or similar
* **with no GenAI or LLM plugins**

##### Language Environments, Compilers and Debuggers
* `gcc` and `gdb`
* `g++` and `gdb`
* `javac` , `java` and `jdb`
* `python` or `pypy` with `pdb`
* `kotlinc`
* ... or similar for your programming language of choice

##### Other
* A browser, such as firefox or chrome
* Core command line utilities that do not break other rules
    * `perf` is okay, `wget` to get disallowed websites is not

#### The contest

The problem set consists of a number of problems (usually 08-12). The problem set will be in English, and given to the participating teams when the contest begins. For each of these problems, you are to write a program in any of the programming languages supported by the Kattis system (see <a href="https://open.kattis.com/languages">here</a> for a list).  The jury guarantees that each problem is solvable in C++ and Java. No guarantees for other languages are given due to the large number of allowed languages, however the jury guarantees that for every language there is at least one problem solvable in that language (it has always been the case that several of the problems were solvable in all available languages, but there is no guarantee of this).

The submitted programs must read from standard input (stdin) and write to standard output (stdout), unless otherwise stated. After you have written a solution, you may submit it using the specified submission system.

The team that solves the most problems correctly wins. If two teams solve the same number of problems, the one with the lowest total time wins. If two top teams end up with the same number of problems solved and the same total time, then the team with
the lowest time on a single problem is ranked higher. If two teams solve the same number of problems, with the same total time, and the same time on all problems, it is a draw. The time for a given problem is the time from the beginning of
the contest to the time when the first correct solution was submitted, plus 20 minutes for each incorrect submission of that problem. The total time is the sum of the times for all solved problems, meaning you will not get extra time for a
problem you never submit a correct solution to.


If you feel that a problem definition is ambiguous, you may submit a clarification request via the submission system. If the judges think there is no ambiguity, you will get a short answer stating this. Otherwise, the judges will write a clarification,
that will be sent to all teams at all sites in the contest.


<hr />

### Change-log

- 2026-05-10 initial publication.
- 2026-09-28 major updates to rules to address many queries to the jury.

</div>
