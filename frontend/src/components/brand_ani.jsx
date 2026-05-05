import React, { useState, useEffect } from "react";
import { motion, useAnimation } from "framer-motion";
import { Briefcase, Users, Mail } from "lucide-react";

const NAVY = "#1e293b";
const BRAND = "#0077b5";
const TICK = "#22c55e";

const START = { x: 30, y: 100 };
const TARGETS = [
  { x: 220, y: 100, label: "Entity",   Icon: Briefcase },
  { x: 450, y: 100, label: "LinkedIn", Icon: Users     },
  { x: 680, y: 100, label: "Email",    Icon: Mail      },
];

const curveBetween = (from, to, lift = 70, alternate = 1) => {
  const midX = (from.x + to.x) / 2;
  const midY = from.y - lift * alternate;
  return {
    x: [from.x, midX, to.x],
    y: [from.y, midY, to.y],
  };
};

const trailPath = (from, to, lift = 70, alternate = 1) => {
  const midX = (from.x + to.x) / 2;
  const midY = from.y - lift * alternate;
  return `M ${from.x} ${from.y} Q ${midX} ${midY} ${to.x} ${to.y}`;
};

const BrandAnimation = () => {
  const controls = useAnimation();
  const [activeStep, setActiveStep] = useState(0);
  const [trailStep, setTrailStep] = useState(0);

  useEffect(() => {
    let cancelled = false;
    const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

    const run = async () => {
      await controls.start({ x: START.x, y: START.y, transition: { duration: 0 } });
      const legs = [
        { from: START,      to: TARGETS[0], lift: 80, alt:  1 },
        { from: TARGETS[0], to: TARGETS[1], lift: 80, alt: -1 },
        { from: TARGETS[1], to: TARGETS[2], lift: 80, alt:  1 },
      ];
      for (let i = 0; i < legs.length; i++) {
        if (cancelled) return;
        const leg = legs[i];
        setTrailStep(i + 1);
        await controls.start({
          ...curveBetween(leg.from, leg.to, leg.lift, leg.alt),
          transition: { duration: 1.4, ease: [0.65, 0, 0.35, 1] },
        });
        if (cancelled) return;
        setActiveStep(i + 1);
        await sleep(1500);
      }
    };
    run();
    return () => { cancelled = true; };
  }, [controls]);

  return (
    <svg
      viewBox="0 0 740 220"
      width="100%"
      height="100%"
      style={{ display: "block", maxHeight: "90px" }}
    >
      <defs>
        <radialGradient id="logoGlow" cx="50%" cy="50%" r="50%">
          <stop offset="0%" stopColor={BRAND} stopOpacity="0.35" />
          <stop offset="100%" stopColor={BRAND} stopOpacity="0" />
        </radialGradient>
        <linearGradient id="logoFill" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stopColor="#3b82f6" />
          <stop offset="100%" stopColor={BRAND} />
        </linearGradient>
      </defs>

      {[
        { from: START,      to: TARGETS[0], lift: 80, alt:  1 },
        { from: TARGETS[0], to: TARGETS[1], lift: 80, alt: -1 },
        { from: TARGETS[1], to: TARGETS[2], lift: 80, alt:  1 },
      ].map((leg, i) => (
        <motion.path
          key={i}
          d={trailPath(leg.from, leg.to, leg.lift, leg.alt)}
          fill="none"
          stroke={BRAND}
          strokeWidth="2"
          strokeDasharray="2 8"
          strokeLinecap="round"
          initial={{ pathLength: 0, opacity: 0 }}
          animate={{ pathLength: trailStep > i ? 1 : 0, opacity: trailStep > i ? 0.55 : 0 }}
          transition={{ duration: 1.4, ease: [0.65, 0, 0.35, 1] }}
        />
      ))}

      <g transform={`translate(${START.x}, ${START.y})`}>
        <circle r="8" fill="#fff" stroke={NAVY} strokeWidth="2" />
        <circle r="3" fill={NAVY} />
        <text y="38" textAnchor="middle" fill={NAVY} fontSize="11"
              fontFamily="ui-sans-serif, system-ui, sans-serif" fontWeight="600"
              letterSpacing="1.2">
          START
        </text>
      </g>

      {TARGETS.map((t, i) => (
        <IconNode
          key={t.label}
          x={t.x}
          y={t.y}
          label={t.label}
          Icon={t.Icon}
          showTick={activeStep >= i + 1}
          isActive={activeStep === i + 1}
        />
      ))}

      <motion.g animate={controls} initial={{ x: START.x, y: START.y }}>
        <circle r="34" fill="url(#logoGlow)" />
        <circle r="20" fill="url(#logoFill)" />
        <circle r="20" fill="none" stroke="#fff" strokeWidth="1.5" opacity="0.4" />
        <path
          d="M -7 -8 L -7 8 L 3 8 Q 9 8 9 3.5 Q 9 0 4 0 Q 9 0 9 -3.5 Q 9 -8 3 -8 Z
             M -3 -4 L 2 -4 Q 4 -4 4 -2.5 Q 4 -1 2 -1 L -3 -1 Z
             M -3 2 L 3 2 Q 5 2 5 3.5 Q 5 5 3 5 L -3 5 Z"
          fill="#fff"
        />
      </motion.g>
    </svg>
  );
};

const IconNode = ({ x, y, label, Icon, showTick, isActive }) => (
  <g transform={`translate(${x}, ${y})`}>
    {isActive && (
      <motion.circle
        r="36"
        fill="none"
        stroke={BRAND}
        strokeWidth="2"
        initial={{ scale: 0.8, opacity: 0.8 }}
        animate={{ scale: 1.4, opacity: 0 }}
        transition={{ duration: 1.4, repeat: Infinity, ease: "easeOut" }}
      />
    )}
    <circle r="32" fill="#ffffff" stroke={showTick ? TICK : NAVY} strokeWidth="2" />
    <foreignObject x="-16" y="-16" width="32" height="32" style={{ overflow: "visible" }}>
      <div style={{
        width: 32, height: 32, display: "flex", alignItems: "center", justifyContent: "center",
        color: showTick ? TICK : NAVY,
      }}>
        <Icon size={22} strokeWidth={2} />
      </div>
    </foreignObject>
    <text y="56" textAnchor="middle" fill={NAVY} fontSize="13"
          fontFamily="ui-sans-serif, system-ui, sans-serif" fontWeight="600">
      {label}
    </text>
    {showTick && (
      <g transform="translate(20, -22)">
        <circle r="11" fill={TICK} />
        <motion.path
          d="M -5 0 L -1 4 L 6 -4"
          stroke="#ffffff"
          strokeWidth="2.5"
          strokeLinecap="round"
          strokeLinejoin="round"
          fill="none"
          initial={{ pathLength: 0 }}
          animate={{ pathLength: 1 }}
          transition={{ duration: 0.5, ease: "easeOut", delay: 0.05 }}
        />
      </g>
    )}
  </g>
);

export default BrandAnimation;
