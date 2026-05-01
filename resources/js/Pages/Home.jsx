import PublicLayout from '../Layouts/PublicLayout';

export default function Home() {
  return (
    <PublicLayout title="Welcome">
      <section className="py-5 bg-body-tertiary border-bottom">
        <div className="container py-4 py-md-5 text-center">
          <h1 className="display-5 display-md-4 fw-bold mb-3">
            Reward Performance.
            <br className="d-none d-md-block" />
            Recognize Excellence.
          </h1>

          <p className="lead text-muted mx-auto mb-4" style={{ maxWidth: '720px' }}>
            A secure internal rewards platform designed to support employee recognition and performance tracking.
          </p>
        </div>
      </section>

      <section className="py-5">
        <div className="container">
          <div className="text-center mb-4 mb-md-5">
            <h2 className="fw-semibold mb-2">How It Works</h2>
            <p className="text-muted">Built for transparency, simplicity, and engagement.</p>
          </div>

          <div className="row g-4 g-md-5">
            <div className="col-12 col-md-4">
              <div className="card h-100 border-0 shadow-sm">
                <div className="card-body text-center p-4">
                  <div
                    className="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3"
                    style={{ width: '70px', height: '70px' }}
                  >
                    <i className="bi bi-award fs-3 text-primary" />
                  </div>
                  <h5 className="fw-semibold">Earn</h5>
                  <p className="text-muted small mb-0">
                    Employees receive tokens for contributions, achievements, and participation.
                  </p>
                </div>
              </div>
            </div>

            <div className="col-12 col-md-4">
              <div className="card h-100 border-0 shadow-sm">
                <div className="card-body text-center p-4">
                  <div
                    className="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-3"
                    style={{ width: '70px', height: '70px' }}
                  >
                    <i className="bi bi-graph-up fs-3 text-success" />
                  </div>
                  <h5 className="fw-semibold">Track</h5>
                  <p className="text-muted small mb-0">
                    Monitor balances and view a complete history of token activity in real time.
                  </p>
                </div>
              </div>
            </div>

            <div className="col-12 col-md-4">
              <div className="card h-100 border-0 shadow-sm">
                <div className="card-body text-center p-4">
                  <div
                    className="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3"
                    style={{ width: '70px', height: '70px' }}
                  >
                    <i className="bi bi-gift fs-3 text-warning" />
                  </div>
                  <h5 className="fw-semibold">Redeem</h5>
                  <p className="text-muted small mb-0">
                    Exchange tokens for approved rewards through a secure redemption process.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
