import { Head, Link, usePage } from '@inertiajs/react';

export default function PublicLayout({ title, children }) {
  const { auth } = usePage().props;
  const user = auth?.user;

  return (
    <>
      <Head title={title} />

      <div className="app-shell min-vh-100 d-flex flex-column">
        <nav className="navbar navbar-expand-lg navbar-light app-nav">
          <div className="container app-container">
            <Link className="navbar-brand app-brand" href="/">
              <span className="brand-mark">
                <i className="bi bi-stars" />
              </span>
              <span>TokenReward</span>
            </Link>

            <button
              className="navbar-toggler app-toggler"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#mainNav"
              aria-controls="mainNav"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span className="navbar-toggler-icon" />
            </button>

            <div className="collapse navbar-collapse" id="mainNav">
              <ul className="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                {!user && (
                  <li className="nav-item">
                    <a className="nav-link nav-action" href="/login">
                      <i className="bi bi-box-arrow-in-right" />
                      Login
                    </a>
                  </li>
                )}

                {user && (
                  <li className="nav-item dropdown">
                    <button className="nav-link dropdown-toggle user-chip" data-bs-toggle="dropdown">
                      <span className="user-dot" />
                      {user.name}
                    </button>
                    <ul className="dropdown-menu dropdown-menu-end">
                      <li>
                        <a className="dropdown-item" href="/dashboard">
                          Dashboard
                        </a>
                      </li>
                      <li>
                        <a className="dropdown-item" href="/orders">
                          Your Orders
                        </a>
                      </li>
                      <li>
                        <a className="dropdown-item" href="/admin/login">
                          Admin Panel
                        </a>
                      </li>
                      <li>
                        <hr className="dropdown-divider" />
                      </li>
                      <li>
                        <Link className="dropdown-item text-danger" href="/logout" method="post" as="button">
                          Logout
                        </Link>
                      </li>
                    </ul>
                  </li>
                )}
              </ul>
            </div>
          </div>
        </nav>

        <main className="flex-grow-1">{children}</main>

        <footer className="app-footer text-center py-3 mt-auto">
          <div className="container app-container">
            <small>&copy; {new Date().getFullYear()} TokenReward</small>
          </div>
        </footer>
      </div>
    </>
  );
}
