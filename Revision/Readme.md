# ISIT307 – Exam Revision Priority Guide

Based on the sample exam format (Overall.md) and the fact the exam is **handwritten** (no autocomplete/syntax check). This is inference from the course materials, not confirmed past papers — use it to prioritize revision time, not as a guarantee of what will appear.

**General rule:** anything needing a long method chain, exact header/API syntax, or multi-class setup → **Part A only, skip for Part B**. Anything that's a single short concept → **drill both A and B**.

---

## 1.1 & 1.2 — Intro / Functions & Control Structures

**Part A:** echo vs print · strong vs loose typing · include vs require · value vs reference · function vs procedure · scope/global · switch vs match

**Part B — High priority:** loops (for/while) · variadic functions · default/named args · global keyword · match expression · return-array functions
> Very hand-writable, likely to appear.

---

## Lecture 2 — Strings & User Input

**Part A:** single vs double quotes · $_GET vs $_POST · htmlspecialchars() purpose · explode/implode · regex anchors · form handler role

**Part B — Medium-high:** string functions (substr, str_replace, explode/implode) · simple regex with a *given* pattern
> Skip: composing full regex patterns from scratch under exam pressure — know how to *read* one more than write one.

---

## Lecture 3 — Files & Directories

**Part A — Prioritize heavily:** file permission octal values · opendir/readdir vs scandir · the 3-step download process · fopen() modes · copy/rename/unlink differences · $_FILES array purpose

**Part B — Low:** skip full upload/download/stream scripts. If it appears, expect a short trace (e.g. fileperms/chmod) rather than a full fopen/fwrite/fclose script.

---

## Lecture 4 — Arrays

**Part A:** push/pop vs unshift/shift · unset vs array_values · slice vs splice · sort variants

**Part B — High priority:** push/pop, splice, associative arrays, 2D array loops
> The Overall.md sample question is literally an array trace. Expect this.

---

## Lecture 5.1 — OOP Part 1

**Part A:** access specifiers · constructor/destructor · accessor/mutator purpose · __get/__set

**Part B — Medium-high:** simple class with constructor + get/set
> Confirmed by the Overall.md sample question. Skip magic methods (__get/__set) as a *write* task — know them for Part A only, too fiddly to hand-write correctly.

---

## Lecture 5.2 — Databases

**Part A — Prioritize heavily:** query() return values · prepared statement advantages · fetch_row vs fetch_assoc · insert_id/affected_rows · why try/catch with mysqli

**Part B — Skip:** full connect/CRUD/prepared-statement scripts are too long and detail-sensitive for handwriting. Not worth rehearsing as write-from-scratch.

---

## Lecture 6.1 — State Information

**Part A — Prioritize heavily:** the four tools · cookies vs sessions comparison table · why setcookie() needs no prior output · session_start() behavior
> This exact question ("explain the tools for maintaining state") is literally in Overall.md's Part A example.

**Part B — Medium:** short cookie/session set + isset() checks are hand-writable and plausible.
> Skip anything involving SID/URL token edge cases as a write task.

---

## Lecture 6.2 — OOP Part 2

**Part A — Prioritize heavily:** extends keyword · protected vs private · abstract class vs interface · trait purpose · __sleep/__wakeup · MVC components

**Part B — Medium:** simple parent/child inheritance with override (short, single concept).
> Skip: abstract classes with two implementers, interfaces, traits across multiple classes as full writes — too long for 6 marks, know them conceptually instead.

---

## Lecture 7.1 — XML/JSON/AJAX

**Part A — Prioritize heavily:** SimpleXML vs DOM · XPath purpose · json_encode/decode + the boolean argument · what AJAX is · readyState/status meaning

**Part B — Skip almost entirely:** too long/verbose to hand-write reliably.
> Exception: json_encode/decode of a small array is short enough to be plausible.

---

## Lecture 7.2 — Recursion & Data Structures

**Part A:** base case + reduction step · static variables in recursion · stack vs queue · tree vs graph · adjacency matrix vs list · SPL purpose

**Part B — High for recursion:** factorial/sum/string-reverse style traces and writes — short, classic, likely.

**Part B — Low for data structures:** skip writing Stack/Queue classes or adjacency lists from scratch; know the concepts for Part A only.

---

## Quick-reference table

| Lecture | Part A priority | Part B priority |
|---|---|---|
| 1.1 & 1.2 | Standard | **High** |
| 2 | Standard | Medium-high |
| 3 | **High** | Low |
| 4 | Standard | **High** |
| 5.1 | Standard | Medium-high |
| 5.2 | **High** | Skip |
| 6.1 | **High** | Medium |
| 6.2 | **High** | Medium |
| 7.1 | **High** | Skip (except JSON) |
| 7.2 | Standard | High (recursion) / Low (data structures) |