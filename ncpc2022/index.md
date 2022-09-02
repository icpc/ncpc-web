---
layout: default
headerText: "NCPC 2022"
title: "Nordic Collegiate Programming Contest 2022"
dateTime: "Saturday October 8th 11:00-16:00 CEST (UTC+2)"
lastUpdate: "2022-08-31"
year: 2022
---
<nav class="navbar menu-bar" markdown="0">
  <a href="#info"><div class="menu-item">Information</div></a>
  <a href="#registration"><div class="menu-item">Registration</div></a>
  <a href="#sites"><div class="menu-item">Sites</div></a>
  <a href="#rules"><div class="menu-item">Rules</div></a>
  <a href="#directors"><div class="menu-item">Site Director</div></a>
  <a href="#organizers"><div class="menu-item">Organizers</div></a>
</nav>
<br />
<h2> {{ page.dateTime }}</h2>
<br />

<div class="bar">
  <a name="info" ></a>
  <h3>Information</h3>
</div>

The Nordic Collegiate Programming Contest {{ page.year }} will take place <b>{{ page.dateTime }}</b>.

<br />
The registration has not opened yet.

<br />

The [rules](#rules) have changed since previous years, please read through them.  Notably:
- NCPC 2022 will take place on site throughout the Nordic countries for the first time since 2019.
- Your team is allowed to compete from a single personal computer.

<br />

The winners will be Nordic Champions in programming. Universities may select student teams that advance to <a href="http://nwerc.eu">NWERC</a>, the regional finals in the <a href="http://icpc.global">ICPC</a> contest. NCPC also encompasses national and local championships.

NCPC will use the Kattis automatic judging system provided by [Kattis](https://kattis.com).  If you have never used it, we encourage you to try it out before the contest to make sure you know how it works.

- [Instructions for the Kattis contest system](https://open.kattis.com/help)
- For a short tutorial on how to use it, go to one of the language-specific help pages available from the previous link.
- If you have not participated before, it is a good idea to look at the [problems from last year's NCPC](https://ncpc21.kattis.com/problems).

<div class="bar">
  <a name="registration"></a>
  <h3>Registration</h3>
</div>
We aim to open the registration by the start of September.

<div class="bar">
  <a name="sites" ></a>
  <h3>Sites</h3>
</div>

Below is a list of sites that will hopefully join NCPC in {{ page.year }}. 

Pending Confirmation means that the site was organized last year, but it has not been confirmed that it will be organized this year.
<!-- Follow the links to get local information such as when and where to meet. -->


<br />

<table class="site-table">
  <tr>
    <th style="text-align: left;"></th>
    <th style="text-align: left;">University</th>
    <th style="text-align: left;">Contact information</th>
    <th style="text-align: left;"></th>
    <th style="text-align: left;">Status</th>
  </tr>
  {% for country in site.data.ncpc2022sites %}
    {% for uni in country.unis %}
      <tr>
        <td><span title="{{ country.name }}">{{ country.emoji }}</span></td>
        <td >
          {% if uni.url %} <a href="{{ uni.url }}"> {% endif %}
            <div>
            {{ uni.name }}
            </div>
            {% for extra_uni in uni.extra_unis %}
              <div> {{ extra_uni.name }} </div>
            {% endfor %}
          {% if uni.url %} </a> {% endif %}
          </td>
        <td style="font-size:12;">
          <div>
          <span>{{ uni.contact }}</span>
          <code>&lt;{{ uni.email }}&gt;</code>
          </div>
          {% for extra_contact in uni.extra_contacts %}
          <div>
            <span>{{ extra_contact.name }}</span>
            <code>&lt;{{ extra_contact.email }}&gt;</code>
          </div>
          {% endfor %}
        </td>
        <td style="font-size:12;">
        {% if uni.contact2 %}
          {{uni.contact2}}<br />
          <code>&lt;{{ uni.email2 }}&gt;</code>
        {% endif %}
        </td>
        <td>{% if uni.confirmed %} ✅ Confirmed {% else %} Pending Confirmation {% endif %} </td>
      </tr>
    {% endfor %}
  {% endfor %}
</table>

<hr />
<br />
If you are a site director please email Måns Magnusson at `exoji2e@gmail.com` to confirm that your site will be hosted. You can also email me if you wish to organize a site at a non-listed university.


<div class="bar">
  <a name="rules"></a>
  <h3>Rules</h3>
</div>


<div class="rules-section" >

<p>
  In short: Teams of up to three persons try to solve as many problems as possible from a set, without external help.
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
  Basically, any student who started his/her university/college studies in {{ page.year | minus: 4 }} or later is eligible to compete.
  For exceptions such as retaken years, military service and so on, please refer to the [ICPC rules](https://icpc.global/regionals/rules).
  Persons who have competed in five regional finals already, or two world finals, may not compete in the ICPC division.
</p>

#### How and where you may compete (temporary rules for 2022)

- In 2022, the contest is held at the participating sites.
- All members of a team have to be present at the same site.
- Your team may bring a single personal computer (with a single keyboard and a single screen) to use during the competition.
- You may not use any additional electronic devices (such as mobile phones) to compete.


#### What you may do during the contest (temporary rules for 2022)

- You may use prewritten code and whatever software is available on your computer.
- You may use any reference materials you have access to in books or on the Internet (e.g. wikipedia and other information your favorite search engine can turn up).
- You may <strong>NOT</strong> communicate with anyone other than the contest organisers and your own team members (in particular you may not communicate with other teams or ask for help on discussion forums).

#### The contest

The problem set consists of a number of problems (usually 8-12). The problem set will be in English, and given to the participating teams when the contest begins. For each of these problems, you are to write a program in any of the programming languages supported by the Kattis system (see <a href="https://open.kattis.com/help">here</a> for a list).  The jury guarantees that each problem is solvable in C++ and Java. No guarantees for other languages are given due to the large number of allowed languages, however the jury guarantees that for every language there is at least one problem solvable in that language (it has always been the case that several of the problems were solvable in all available languages, but there is no guarantee of this).

The submitted programs must read from standard input (stdin) and write to standard output (stdout), unless otherwise stated. After you have written a solution, you may submit it using the specified submission system.

The team that solves the most problems correctly wins. If two teams solve the same number of problems, the one with the lowest total time wins. If two top teams end up with the same number of problems solved and the same total time, then the team with
the lowest time on a single problem is ranked higher. If two teams solve the same number of problems, with the same total time, and the same time on all problems, it is a draw. The time for a given problem is the time from the beginning of
the contest to the time when the first correct solution was submitted, plus 20 minutes for each incorrect submission of that problem. The total time is the sum of the times for all solved problems, meaning you will not get extra time for a
problem you never submit a correct solution to.


If you feel that a problem definition is ambiguous, you may submit a clarification request via the submission system. If the judges think there is no ambiguity, you will get a short answer stating this. Otherwise, the judges will write a clarification,
that will be sent to all teams at all sites in the contest.


</div>

<div class="bar">
  <a name="directors" />
  <h3>Site Director</h3>
</div>
Each site has a site director. The site director is responsible for running the local site during the contest, preferably with the help of a local group. The following conditions should be met at each site:

- Participating teams should be seated in designated area with one table per team and ample space between teams.
- Each team should have close access to a power outlet for their computer.
- Each team should receive 3 printed copies of the problem statements in an envelope in the minutes before the contest starts, which they should open when the contest starts.

<div class="bar">
  <a name="organizers" />
  <h3>Organizers</h3>
</div>

<table>
  <tr>
    <td>NCPC director:</td>
    <td>Fredrik Niemelä (Kattis)</td>
  </tr>
  <tr>
    <td>Head of Jury:</td>
    <td>Nils Gustafsson (KTH Royal Institute of Technology)</td>
  </tr>
  <tr>
    <td>Technical Director:</td>
    <td>Pehr Söderman (Kattis)</td>
  </tr>
  <tr>
    <td>Webmaster:</td>
    <td>Måns Magnusson (Lund University)</td>
  </tr>
</table>

<hr />  
<br />

Last updated: {{page.lastUpdate}}

Webmaster: Måns Magnusson `exoji2e@gmail.com`

<br />