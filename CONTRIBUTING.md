# Contributing

First, thank you for taking the time to contribute to [PostTypes](https://github.com/jjgrainger/PostTypes), you're amazing! 🎉 👏 🙌

All contributions are welcome and appreciated. It is recommended that you read through this document before making your first contribution.

The following instructions are recommended as _guidelines_ and not strict rules. However, following this guide will make it easier for both you and the maintainers when working on the project.

**There are 3 ways you can contribute to PostTypes.**

* [Create an Issue](#create-an-issue)
* [Submit a Pull Request](#submit-a-pull-request)
* [Show Support](#show-support)

## Purpose

PostTypes mission is to _create advanced WordPress custom post types easily_.

Its focus is on the creation of custom post types, specifically around the admin interface, providing methods to create advanced post tables, columns, filters and more.

It also makes it easy to create taxonomies and assign them to post types.

Although its focus is on the admin interface, PostTypes **is not for custom fields**. There are plenty of solutions for creating custom fields in WordPress. PostTypes has no intention of being one.

Please keep this in mind when contributing to the project.

## Create an Issue

If you would like to report a bug, feature request, documentation improvement or question please [create an issue](https://github.com/jjgrainger/PostTypes/issues/new). Before creating a new issue please check it has not already been raised by searching [issues](https://github.com/jjgrainger/PostTypes/issues) on GitHub.

When creating an issue it is best to provide as much information as possible in order to help the discussion move quickly and efficiently.

**There are 4 types of issues:**

* [Bug Reports](#bug-report)
* [Feature Requests](#feature-request)
* [Documentation Improvements](#documentation-improvement)
* [Support Questions](#support-questions)

### 🐛 Bug Report

Bug reports highlight an error or unexpected behaviour when using the code. In order to resolve the issue, enough detail must be provided in order to recreate the problem so it can be investigated and fixed.

**Tips on creating bug reports:**

* Provide as much detail about the problem.
* Give steps to help reproduce the problem.
* Code examples and error messages are useful.
* Version numbers (PHP, PostTypes, WordPress) are useful.

### 🚀 Feature Request

Feature requests suggest an idea for an improvement or additional functionality. Feature requests should be raised **before submitting a [pull request](#submit-a-pull-request)**. This provides an opportunity for discussion and help prevent unnecessary work from being carried out.

**Tips on creating feature requests:**

* Provide a description of the change you want to make.
* Highlight the problem it attempts to solve.
* Offer examples of how it would be used.
* Provide links to WordPress documentation where relevant.

### 📖 Documentation Improvements

Documentation improvements suggest ways to enhance the [documentation](https://posttypes.jjgrainger.co.uk). This could be anything from fixing spelling errors to adding new sections.

**Tips on creating documentation improvements:**

* Provide links to the pages you're referring to.
* Offer an explanation on how this is an improvement.

### 🎈 Support Questions

For general questions and support. This is also a catch-all for anything that doesn't fit in the categories above.

**Tips on creating support questions:**

* Please check the [documentation](https://posttypes.jjgrainger.co.uk) first for your answer.
* Provide code examples if necessary.
* Keep questions relevant to PostTypes.

### Labels

Labels are used to help organise different types of issues. Labels are prefixed with their group, which currently are _type_ and _status_. Maintainers will apply the correct labels to an issue when they are reviewed.

| Label | Description |
| --- | --- |
| **Type: Bug** | Bug reports and issues with unexpected behaviour |
| **Type: Feature** | Feature requests and ideas |
| **Type: Docs** | Improvements and fixes around documentation |
| **Type: Support** | General support and questions |
| **Status: Discussion** | An issue that needs discussion before it can be worked on. |
| **Status: Ready** | An issue that is ready to be picked up. |
| **Status: In Progress** | An issue that is currently being worked. |
| **Status: Review** | An issue that is finished and ready to be reviewed and merged. |
| **Status: Complete** | An issue that is finished and merged into master. |

This is not an exhaustive list. Labels may be changed and new ones created over time. For a complete list, see the [GitHub repo](https://github.com/jjgrainger/PostTypes/labels).

## Submit a Pull Request

**Before submitting a Pull Request** it is recommended you [create an issue](#create-an-issue) first. This provides an opportunity to open up a discussion before any work takes place.

### Basic Workflow

1. [Create a Fork](https://guides.github.com/activities/forking/#fork) of the main [jjgrainger/PostTypes](https://github.com/jjgrainger/PostTypes) repository.
1. [Clone your fork](https://guides.github.com/activities/forking/#clone) locally to your machine.
1. Create a branch with one of the relevant [branch prefixes](#branch-prefixes).
1. Commit work to your branch.
1. Push your branch to your fork on GitHub.
1. Create a [pull request](https://github.com/jjgrainger/PostTypes/compare) comparing your forks branch against the main repository's `master`.

**Tips on creating pull requests:**

* [Reference issue numbers](https://help.github.com/articles/closing-issues-using-keywords/) in your commits where applicable.
* Commit *little and often*, smaller commits make it easier to review code.
* **Do not** include issue numbers in the title of your pull request.
* Please provide a description with your pull request with details about the change you are trying to make.
* Please link to any issues the pull request is related.

### Branch Prefixes

Branch prefixes are used to help categorise the working branches by type.

| Prefix | Description | Example |
| --- | --- | --- |
| `fix/` | An attempt to fix a bug | `fix/incorrect-post-type-name` |
| `feature/` | A new feature being worked on | `feature/bulk-update-actions` |
| `docs/` | Documentation fix, addition or improvement | `docs/fix-spelling-errors` |
| `release/` | A release branch to create a new release. | `release/v2.0.1` |

The `release/` prefix is to be used by **maintainers only**.

The `docs/` prefix is used alongside [Gitbook](https://www.gitbook.com/) where the docs are hosted. Branches with this prefix will be automatically generated and made available as a version on the [documentation site](https://posttypes.jjgrainger.co.uk). This provides an opportunity to preview changes and approve them before being merged.

## Show Support

PostTypes is open source and free to use. It was created to solve a problem while giving something back to the community. There are many ways to show your support, some ideas include:

* Creating an [issue](https://github.com/jjgrainger/PostTypes/issues/new) and [pull requests](https://github.com/jjgrainger/PostTypes/compare).
* [Staring](https://github.com/jjgrainger/PostTypes/stargazers) the project on GitHub.
* Saying "thank you" over on [Twitter](https://twitter.com/jjgrainger)
* Spread the word and let others know.
* [Buy me a beer](https://www.paypal.me/jjgrainger/5).
