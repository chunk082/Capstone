import PublicLayout from '../Layouts/PublicLayout';
import { Link, usePage } from '@inertiajs/react';
import { useMemo, useState } from 'react';

function MetricCard({ icon, label, value, suffix, tone }) {
  return (
    <div className="col-12 col-md-6 col-xl-3">
      <div className="metric-card h-100">
        <div className={`metric-icon ${tone}`}>
          <i className={`bi ${icon}`} />
        </div>
        <div>
          <p>{label}</p>
          <strong>
            {value}
            {suffix && <span> {suffix}</span>}
          </strong>
        </div>
      </div>
    </div>
  );
}

export default function Dashboard() {
  const { metrics, products, flash } = usePage().props;
  const [selectedProduct, setSelectedProduct] = useState(null);

  const currentBalance = Number(metrics?.currentBalance ?? 0);
  const earnedThisMonth = Number(metrics?.earnedThisMonth ?? 0);
  const redeemedThisMonth = Number(metrics?.redeemedThisMonth ?? 0);
  const pendingRequests = Number(metrics?.pendingRequests ?? 0);
  const productList = useMemo(() => (Array.isArray(products) ? products : []), [products]);

  const metricCards = [
    {
      icon: 'bi-wallet2',
      label: 'Current Balance',
      value: currentBalance,
      suffix: 'Tokens',
      tone: 'blue',
    },
    {
      icon: 'bi-arrow-up-right',
      label: 'Earned This Month',
      value: earnedThisMonth,
      suffix: 'Tokens',
      tone: 'green',
    },
    {
      icon: 'bi-bag-check',
      label: 'Redeemed This Month',
      value: redeemedThisMonth,
      suffix: 'Tokens',
      tone: 'amber',
    },
    {
      icon: 'bi-clock-history',
      label: 'Pending Requests',
      value: pendingRequests,
      tone: 'red',
    },
  ];

  return (
    <PublicLayout title="Dashboard">
      <section className="dashboard-section">
        <div className="container app-container">
          <div className="row g-3">
            {metricCards.map((metric) => (
              <MetricCard key={metric.label} {...metric} />
            ))}
          </div>

          {(flash?.success || flash?.error) && (
            <div className="mt-4">
              {flash?.success && <div className="alert alert-success app-alert mb-2">{flash.success}</div>}
              {flash?.error && <div className="alert alert-danger app-alert mb-2">{flash.error}</div>}
            </div>
          )}

          <div className="products-heading">
            <div>
              <span className="eyebrow">Catalog</span>
              <h2>Available Products</h2>
            </div>
            <span className="catalog-count">{productList.length} item(s)</span>
          </div>

          {productList.length === 0 && (
            <div className="empty-state">
              <i className="bi bi-box-seam" />
              <span>No products are available right now.</span>
            </div>
          )}

          <div className="row g-3 g-xl-4">
            {productList.map((product) => {
              const tokenCost = Number(product.token_cost);
              const stock = Number(product.stock);
              const canRedeem = currentBalance >= tokenCost && stock > 0;

              return (
                <div key={product.id} className="col-12 col-md-6 col-xl-4">
                  <article className="product-card h-100">
                    <div className="product-image">
                      {product.image_url ? (
                        <img src={product.image_url} alt={product.name} />
                      ) : (
                        <div className="product-placeholder">
                          <i className="bi bi-image" />
                          <span>No image</span>
                        </div>
                      )}
                      <span className="stock-pill">{stock} in stock</span>
                    </div>

                    <div className="product-body">
                      <div>
                        <h3>{product.name}</h3>
                        <p>
                          <i className="bi bi-coin" />
                          {tokenCost} tokens
                        </p>
                      </div>

                      <div className="product-actions">
                        <Link
                          href={`/products/${product.id}/redeem`}
                          method="post"
                          as="button"
                          className="btn app-btn app-btn-primary flex-fill"
                          disabled={!canRedeem}
                        >
                          <i className="bi bi-bag-check" />
                          {canRedeem ? 'Redeem' : 'Need More'}
                        </Link>

                        <button
                          type="button"
                          className="btn app-btn app-btn-secondary flex-fill"
                          onClick={() => setSelectedProduct(product)}
                        >
                          <i className="bi bi-eye" />
                          Details
                        </button>
                      </div>
                    </div>
                  </article>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {selectedProduct && (
        <div className="app-modal-backdrop" onClick={() => setSelectedProduct(null)}>
          <div className="app-modal" onClick={(event) => event.stopPropagation()}>
            <div className="app-modal-header">
              <div>
                <span className="eyebrow">Product details</span>
                <h3>{selectedProduct.name}</h3>
              </div>
              <button
                type="button"
                className="icon-button"
                onClick={() => setSelectedProduct(null)}
                aria-label="Close"
              >
                <i className="bi bi-x-lg" />
              </button>
            </div>
            <div className="app-modal-body">
              {selectedProduct.image_url && <img src={selectedProduct.image_url} alt={selectedProduct.name} />}
              <p>{selectedProduct.description || 'No description available.'}</p>
              <div className="detail-row">
                <span>Cost</span>
                <strong>{Number(selectedProduct.token_cost)} tokens</strong>
              </div>
              <div className="detail-row">
                <span>Stock</span>
                <strong>{Number(selectedProduct.stock)}</strong>
              </div>
            </div>
          </div>
        </div>
      )}
    </PublicLayout>
  );
}
