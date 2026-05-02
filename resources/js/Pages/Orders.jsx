import PublicLayout from '../Layouts/PublicLayout';
import { Link, usePage } from '@inertiajs/react';
import { useMemo } from 'react';

const statusTone = {
  Pending: 'pending',
  Processing: 'processing',
  Shipped: 'shipped',
  Completed: 'completed',
  Cancelled: 'cancelled',
};

function formatDate(value) {
  if (!value) {
    return 'Not available';
  }

  return new Intl.DateTimeFormat(undefined, {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  }).format(new Date(value));
}

export default function Orders() {
  const { orders } = usePage().props;
  const orderList = useMemo(() => (Array.isArray(orders) ? orders : []), [orders]);

  return (
    <PublicLayout title="Your Orders">
      <section className="orders-section">
        <div className="container app-container">
          <div className="orders-header">
            <div>
              <span className="eyebrow">Order status</span>
              <h1>Your Orders</h1>
              <p>Track reward requests, fulfillment status, transaction details, and tracking numbers.</p>
            </div>
            <Link className="btn app-btn app-btn-secondary" href="/dashboard">
              <i className="bi bi-arrow-left" />
              Dashboard
            </Link>
          </div>

          {orderList.length === 0 && (
            <div className="empty-state orders-empty">
              <i className="bi bi-receipt" />
              <div>
                <strong>No orders yet.</strong>
                <span>Redeemed products will appear here after you submit a reward request.</span>
              </div>
            </div>
          )}

          <div className="orders-list">
            {orderList.map((order) => {
              const tone = statusTone[order.status] || 'pending';

              return (
                <article className="order-card" key={order.id}>
                  <div className="order-product">
                    <div className="order-image">
                      {order.product?.image_url ? (
                        <img src={order.product.image_url} alt={order.product.name} />
                      ) : (
                        <i className="bi bi-box-seam" />
                      )}
                    </div>
                    <div>
                      <h2>{order.product?.name || 'Reward product'}</h2>
                      <span>{order.transaction_id}</span>
                    </div>
                  </div>

                  <div className="order-details">
                    <div className="order-detail">
                      <span>Status</span>
                      <strong className={`order-status ${tone}`}>{order.status}</strong>
                    </div>
                    <div className="order-detail">
                      <span>Tokens Spent</span>
                      <strong>{Number(order.tokens_spent)} tokens</strong>
                    </div>
                    <div className="order-detail">
                      <span>Tracking Number</span>
                      <strong>{order.tracking_number || 'Not assigned yet'}</strong>
                    </div>
                    <div className="order-detail">
                      <span>Ordered</span>
                      <strong>{formatDate(order.created_at)}</strong>
                    </div>
                    <div className="order-detail">
                      <span>Last Updated</span>
                      <strong>{formatDate(order.updated_at)}</strong>
                    </div>
                  </div>
                </article>
              );
            })}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
