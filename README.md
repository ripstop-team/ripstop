# ripstop

Stop your codes security holes from getting larger.

This tool checks your code against the service of [RIPStech](https://ripstech.com) and
allows you to receive notifications from your CI-Pipeline about the security holes in
your app.

You will need an account at RIPStech to use this.

## Installation

Install this globaly using [composer](https://getcomposer.org):

```bash
composer global install ripstop/ripstop
```

There might be a PHAR in future time but not now.

## Usage:

Before you can use the tool you will need to copy the configuration file
`ripstop.dist.yml` to `ripstop.yml` and replace the configuration parameters
with "real values"

Then you can use it to retrieve the report of the last run against your code
like this:

```bash
ripstop reports:pdf <applicationId> [<scanId>] [--filename=/path/to/store/the/PDF/report/in.pdf]
```

This will download the report to `/path/to/store/the/PDF/report/in.pdf`
