import { Head, Link, usePage } from '@inertiajs/react';

export default function PublicLayout({ title, children }) {
  const { auth } = usePage().props;
  const user = auth?.user;

  return (
    <>
      <Head title={title} />

      <div className="min-vh-100 d-flex flex-column">
        <nav className="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
          <div className="container">
            <Link className="navbar-brand fw-bold" href="/">
              TokenReward
            </Link>

            <button
              className="navbar-toggler"
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
              <ul className="navbar-nav ms-auto">
                {!user && (
                  <li className="nav-item">
                    <a className="nav-link login-link fw-semibold" href="/login">
                      Login
                    </a>
                  </li>
                )}

                {user && (
                  <li className="nav-item dropdown">
                    <button className="nav-link dropdown-toggle btn btn-link text-white text-decoration-none" data-bs-toggle="dropdown">
                      {user.name}
                    </button>
                    <ul className="dropdown-menu dropdown-menu-end">
                      <li>
                        <a className="dropdown-item" href="/dashboard">
                          Dashboard
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

        <footer className="bg-dark text-white text-center py-3 mt-auto">
          <div className="container">
            <small>&copy; {new Date().getFullYear()} TokenReward</small>
          </div>
        </footer>
      </div>
    </>
  );
}
