/**
 * AlgorithmicBackground — Animated SVG pattern that renders
 * layered geometric shapes with slow drift animations.
 */
import { useEffect, useRef } from 'react';
import './AlgorithmicBackground.css';

export default function AlgorithmicBackground() {
  const canvasRef = useRef(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let animationId;
    let time = 0;

    function resize() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    function drawNode(x, y, radius, alpha) {
      ctx.beginPath();
      ctx.arc(x, y, radius, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(64, 112, 244, ${alpha})`;
      ctx.fill();
    }

    function drawLine(x1, y1, x2, y2, alpha) {
      ctx.beginPath();
      ctx.moveTo(x1, y1);
      ctx.lineTo(x2, y2);
      ctx.strokeStyle = `rgba(64, 112, 244, ${alpha})`;
      ctx.lineWidth = 0.5;
      ctx.stroke();
    }

    const nodes = Array.from({ length: 35 }, () => ({
      x: Math.random() * window.innerWidth,
      y: Math.random() * window.innerHeight,
      vx: (Math.random() - 0.5) * 0.3,
      vy: (Math.random() - 0.5) * 0.3,
      radius: Math.random() * 2 + 1,
    }));

    function animate() {
      time += 0.005;
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      // Update positions
      nodes.forEach((node) => {
        node.x += node.vx;
        node.y += node.vy;

        if (node.x < 0 || node.x > canvas.width) node.vx *= -1;
        if (node.y < 0 || node.y > canvas.height) node.vy *= -1;
      });

      // Draw connections
      for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
          const dx = nodes[i].x - nodes[j].x;
          const dy = nodes[i].y - nodes[j].y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 200) {
            const alpha = (1 - dist / 200) * 0.08;
            drawLine(nodes[i].x, nodes[i].y, nodes[j].x, nodes[j].y, alpha);
          }
        }
      }

      // Draw nodes
      nodes.forEach((node) => {
        const pulse = Math.sin(time * 2 + node.x * 0.01) * 0.02 + 0.05;
        drawNode(node.x, node.y, node.radius, pulse);
      });

      // Draw floating hexagons
      for (let i = 0; i < 5; i++) {
        const cx = canvas.width * (0.15 + i * 0.18);
        const cy = canvas.height * 0.5 + Math.sin(time + i) * 40;
        const size = 30 + Math.sin(time * 0.5 + i * 0.7) * 10;
        drawHexagon(ctx, cx, cy, size, 0.015 + Math.sin(time + i) * 0.005);
      }

      animationId = requestAnimationFrame(animate);
    }

    function drawHexagon(ctx, cx, cy, size, alpha) {
      ctx.beginPath();
      for (let i = 0; i < 6; i++) {
        const angle = (Math.PI / 3) * i - Math.PI / 6;
        const x = cx + size * Math.cos(angle);
        const y = cy + size * Math.sin(angle);
        if (i === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      }
      ctx.closePath();
      ctx.strokeStyle = `rgba(0, 201, 255, ${alpha})`;
      ctx.lineWidth = 1;
      ctx.stroke();
    }

    animate();

    return () => {
      window.removeEventListener('resize', resize);
      cancelAnimationFrame(animationId);
    };
  }, []);

  return (
    <>
      <canvas ref={canvasRef} className="algo-canvas" aria-hidden="true" />
      <div className="algo-grid" aria-hidden="true" />
    </>
  );
}
