import PublicLayout from '../Layouts/PublicLayout';

const steps = [
  {
    icon: 'bi-award',
    label: 'Earn',
    tone: 'blue',
    copy: 'Recognize meaningful work with tokens tied to contributions, milestones, and participation.',
  },
  {
    icon: 'bi-activity',
    label: 'Track',
    tone: 'green',
    copy: 'Give employees a clear view of balances, activity, and pending reward requests.',
  },
  {
    icon: 'bi-bag-check',
    label: 'Redeem',
    tone: 'amber',
    copy: 'Turn earned tokens into approved rewards with a simple internal redemption flow.',
  },
];

export default function Home() {
  return (
    <PublicLayout title="Welcome">
      <section className="hero-section">
        <div className="container app-container">
          <div className="row align-items-center g-4 g-xl-5">
            <div className="col-12 col-lg-6">
              <span className="eyebrow">Employee rewards platform</span>
              <h1 className="hero-title">Reward performance with a sharper, simpler token experience.</h1>
              <p className="hero-copy">
                TokenReward gives teams a focused place to recognize contributions, monitor balances, and redeem
                approved rewards without adding operational noise.
              </p>
            </div>

            <div className="col-12 col-lg-6">
              <div className="program-preview" aria-label="Rewards program overview">
                <div className="program-preview-header">
                  <span className="eyebrow">Program Flow</span>
                  <h2>Recognize, approve, and fulfill rewards from one internal system.</h2>
                </div>

                <div className="program-step">
                  <div className="program-step-icon blue">
                    <i className="bi bi-person-check" />
                  </div>
                  <div>
                    <strong>Employee recognition</strong>
                    <span>Managers assign tokens for contributions and milestones.</span>
                  </div>
                </div>

                <div className="program-step">
                  <div className="program-step-icon green">
                    <i className="bi bi-ui-checks-grid" />
                  </div>
                  <div>
                    <strong>Reward catalog</strong>
                    <span>Employees browse approved products and submit redemption requests.</span>
                  </div>
                </div>

                <div className="program-step">
                  <div className="program-step-icon amber">
                    <i className="bi bi-truck" />
                  </div>
                  <div>
                    <strong>Admin fulfillment</strong>
                    <span>Staff manage inventory, orders, statuses, and tracking details.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="workflow-section">
        <div className="container app-container">
          <div className="section-heading">
            <span className="eyebrow">Workflow</span>
            <h2>Built around the three actions employees actually need.</h2>
          </div>

          <div className="row g-3 g-lg-4">
            {steps.map((step) => (
              <div className="col-12 col-md-4" key={step.label}>
                <div className="feature-card h-100">
                  <div className={`feature-icon ${step.tone}`}>
                    <i className={`bi ${step.icon}`} />
                  </div>
                  <h3>{step.label}</h3>
                  <p>{step.copy}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
