#!/bin/bash
set -e

echo "=========================================="
echo "Cusdis Self-Hosted Comments Setup"
echo "=========================================="
echo ""

# Detect domain
if [ -f /container/application/.env ]; then
    DOMAIN=$(grep APP_URL /container/application/.env | cut -d '=' -f2 | sed 's|https://||' | sed 's|http://||' | sed 's|/||')
    echo "Detected domain: $DOMAIN"
else
    DOMAIN="localhost"
fi

INSTALL_DIR="$HOME/cusdis"

echo ""
echo "Step 1: Creating installation directory..."
mkdir -p $INSTALL_DIR
cd $INSTALL_DIR

echo ""
echo "Step 2: Installing Cusdis..."
npm install -g cusdis

echo ""
echo "Step 3: Initializing Cusdis..."
npx cusdis init

echo ""
echo "Step 4: Creating systemd service..."

# Create systemd service file
cat > /tmp/cusdis.service <<EOF
[Unit]
Description=Cusdis Comment System
After=network.target

[Service]
Type=simple
User=$USER
WorkingDirectory=$INSTALL_DIR
ExecStart=$(which node) $(which cusdis) start
Restart=always
Environment="PORT=3000"
Environment="NODE_ENV=production"

[Install]
WantedBy=multi-user.target
EOF

echo ""
echo "=========================================="
echo "Setup Instructions"
echo "=========================================="
echo ""
echo "1. Copy service file to systemd:"
echo "   sudo cp /tmp/cusdis.service /etc/systemd/system/"
echo ""
echo "2. Start Cusdis:"
echo "   sudo systemctl daemon-reload"
echo "   sudo systemctl enable cusdis"
echo "   sudo systemctl start cusdis"
echo ""
echo "3. Check status:"
echo "   sudo systemctl status cusdis"
echo ""
echo "4. Configure Apache proxy (add to your VirtualHost):"
echo ""
echo "   ProxyPass /cusdis http://127.0.0.1:3000/"
echo "   ProxyPassReverse /cusdis http://127.0.0.1:3000/"
echo ""
echo "5. Access admin panel:"
echo "   https://$DOMAIN/cusdis"
echo ""
echo "Installation directory: $INSTALL_DIR"
echo ""
