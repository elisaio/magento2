# Releasing

To release a change, include the same new extension version in both:

- `composer.json`: `version`
- `etc/di.xml`: the `extension` item in `Elisa\ProductApi\Model\Data\VersionInfo`

Use a stable `X.Y.Z` version higher than the existing release tags. Change an
individual API version in `etc/di.xml` only when that API changes; it does not
need to match the extension version.

Merge the PR into `master`. The **Release** GitHub Actions workflow validates
the versions, pushes the matching tag (without a `v` prefix), and publishes a
GitHub release with generated release notes. A merge without a new version
leaves the existing release unchanged. Existing drafts are left for a maintainer
to publish manually.

The repository's Packagist push webhook then requests a refresh of
[`elisa/product_api`](https://packagist.org/packages/elisa/product_api).
Its secret is the maintainer account's Packagist **Safe API Token**, which only
allows package refreshes. Do not commit the token. If the token is rotated,
update the webhook secret in GitHub repository settings.

Check **Actions → Release**, the GitHub release page, and the Packagist version
list after a release. To retry a failed run, rerun it or use **Run workflow**
on `master`. An existing tag is reused, so a release creation failure does not
require moving or deleting a tag.

Publishing makes the package available; merchants must still update their
Composer dependency and deploy it using their normal Magento process.
