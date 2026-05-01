import PublicLayout from '../Layouts/PublicLayout';
import { Link, usePage } from '@inertiajs/react';
import { useMemo, useState } from 'react';

export default function Dashboard() {
  const { metrics, products, flash } = usePage().props;
  const [selectedProduct, setSelectedProduct] = useState(null);

  const currentBalance = Number(metrics?.currentBalance ?? 0);
  const earnedThisMonth = Number(metrics?.earnedThisMonth ?? 0);
  const redeemedThisMonth = Number(metrics?.redeemedThisMonth ?? 0);
  const pendingRequests = Number(metrics?.pendingRequests ?? 0);
  const productList = useMemo(() => (Array.isArray(products) ? products : []), [products]);

  return (
    <PublicLayout title="Dashboard">
      <section className="py-4 py-md-5">
        <div className="container">
          <div className="mb-4">
            <div>
              <h1 className="h3 mb-1">Dashboard</h1>
              <p className="text-muted mb-0">Your rewards activity at a glance.</p>
            </div>
          </div>

          <div className="row g-3">
            <div className="col-12 col-md-6 col-lg-3">
              <div className="card border-0 shadow-sm h-100">
                <div className="card-body">
                  <p className="text-muted small mb-1">Current Balance</p>
                  <h4 className="mb-0">{currentBalance} Tokens</h4>
                </div>
              </div>
            </div>
            <div className="col-12 col-md-6 col-lg-3">
              <div className="card border-0 shadow-sm h-100">
                <div className="card-body">
                  <p className="text-muted small mb-1">Earned This Month</p>
                  <h4 className="mb-0">{earnedThisMonth} Tokens</h4>
                </div>
              </div>
            </div>
            <div className="col-12 col-md-6 col-lg-3">
              <div className="card border-0 shadow-sm h-100">
                <div className="card-body">
                  <p className="text-muted small mb-1">Redeemed This Month</p>
                  <h4 className="mb-0">{redeemedThisMonth} Tokens</h4>
                </div>
              </div>
            </div>
            <div className="col-12 col-md-6 col-lg-3">
              <div className="card border-0 shadow-sm h-100">
                <div className="card-body">
                  <p className="text-muted small mb-1">Pending Requests</p>
                  <h4 className="mb-0">{pendingRequests}</h4>
                </div>
              </div>
            </div>
          </div>

          {(flash?.success || flash?.error) && (
            <div className="mt-4">
              {flash?.success && <div className="alert alert-success mb-2">{flash.success}</div>}
              {flash?.error && <div className="alert alert-danger mb-2">{flash.error}</div>}
            </div>
          )}

          <div className="d-flex align-items-center justify-content-between mt-5 mb-3">
            <h2 className="h4 mb-0">Available Products</h2>
            <small className="text-muted">{productList.length} item(s)</small>
          </div>

          {productList.length === 0 && (
            <div className="card border-0 shadow-sm">
              <div className="card-body py-4 text-muted">No products are available right now.</div>
            </div>
          )}

          <div className="row g-3">
            {productList.map((product) => {
              const canRedeem = currentBalance >= Number(product.token_cost) && Number(product.stock) > 0;

              return (
                <div key={product.id} className="col-12 col-md-6 col-xl-4">
                  <div className="card border-0 shadow-sm h-100">
                    <div style={{ height: '220px' }} className="bg-light border-bottom">
                      {product.image_url ? (
                        <img
                          src={product.image_url}
                          alt={product.name}
                          className="w-100 h-100"
                          style={{ objectFit: 'cover' }}
                        />
                      ) : (
                        <div className="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                          No image
                        </div>
                      )}
                    </div>

                    <div className="card-body d-flex flex-column">
                      <h5 className="card-title mb-1">{product.name}</h5>
                      <p className="text-muted small mb-3">
                        {Number(product.token_cost)} tokens • {Number(product.stock)} in stock
                      </p>

                      <div className="mt-auto d-flex gap-2">
                        <Link
                          href={`/products/${product.id}/redeem`}
                          method="post"
                          as="button"
                          className="btn btn-primary btn-sm flex-fill"
                          disabled={!canRedeem}
                        >
                          {canRedeem ? 'Redeem' : 'Not Enough Tokens'}
                        </Link>

                        <button
                          type="button"
                          className="btn btn-outline-secondary btn-sm flex-fill"
                          onClick={() => setSelectedProduct(product)}
                        >
                          View Product
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {selectedProduct && (
        <div
          className="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3"
          style={{ background: 'rgba(0,0,0,0.45)', zIndex: 1055 }}
          onClick={() => setSelectedProduct(null)}
        >
          <div
            className="bg-white rounded shadow w-100"
            style={{ maxWidth: '640px' }}
            onClick={(event) => event.stopPropagation()}
          >
            <div className="d-flex align-items-center justify-content-between border-bottom p-3">
              <h5 className="mb-0">{selectedProduct.name}</h5>
              <button
                type="button"
                className="btn-close"
                onClick={() => setSelectedProduct(null)}
                aria-label="Close"
              />
            </div>
            <div className="p-3">
              {selectedProduct.image_url && (
                <img
                  src={selectedProduct.image_url}
                  alt={selectedProduct.name}
                  className="w-100 rounded border mb-3"
                  style={{ maxHeight: '280px', objectFit: 'cover' }}
                />
              )}
              <p className="mb-3">{selectedProduct.description || 'No description available.'}</p>
              <div className="small text-muted">
                Cost: {Number(selectedProduct.token_cost)} tokens • Stock: {Number(selectedProduct.stock)}
              </div>
            </div>
            <div className="border-top p-3 d-flex justify-content-end">
              <button type="button" className="btn btn-outline-secondary" onClick={() => setSelectedProduct(null)}>
                Close
              </button>
            </div>
          </div>
        </div>
      )}
    </PublicLayout>
  );
}
